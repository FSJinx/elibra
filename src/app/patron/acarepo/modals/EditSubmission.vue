<template>
  <Button size="sm" variant="primary" @click="open">Review & Resubmit</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Review and Resubmit" subtitle="Update your rejected manuscript and send it for review again." icon="mortarboard" />
    <ModalBody class="max-h-[70vh] overflow-y-auto p-5">
      <Alert v-if="error" variant="danger">{{ error }}</Alert>
      <p v-if="data.statusRemark" class="mb-4 text-sm text-danger">
        <strong>Reviewer remarks:</strong> {{ data.statusRemark }}
      </p>

      <Form id="edit-manuscript-form" class="grid gap-5 sm:grid-cols-2" @submit="submit">
        <Control id="edit-manuscript-title" required col class="sm:col-span-2">
          <Label>Title</Label>
          <Input v-model="form.title" :max="255" :disabled="loading" :error="fieldErrors?.title?.[0]" />
        </Control>
        <Control id="edit-manuscript-category" required col>
          <Label>Category</Label>
          <Select v-model="form.category" :disabled="loading" :error="fieldErrors?.category?.[0]">
            <Option value="" disabled>Select manuscript category</Option>
            <Option value="ut">Undergraduate thesis</Option>
            <Option value="gt">Graduate thesis</Option>
          </Select>
        </Control>
        <Control id="edit-manuscript-language" required col>
          <Label>Language</Label>
          <Select v-model="form.language_id" :disabled="loading" :error="fieldErrors?.language_id?.[0]">
            <Option :value="null" disabled>Select language</Option>
            <Option v-for="language in options.languages" :key="language.id" :value="language.id">{{ language.name }}</Option>
          </Select>
        </Control>
        <Control id="edit-manuscript-library" required col class="sm:col-span-2">
          <Label>Library</Label>
          <Select v-model="form.library_id" :disabled="loading || !campusLibraries.length" :error="fieldErrors?.library_id?.[0]">
            <Option :value="null" disabled>Select your campus library</Option>
            <Option v-for="library in campusLibraries" :key="library.id" :value="library.id">{{ library.name }}</Option>
          </Select>
          <p v-if="!loading && !campusLibraries.length" class="text-sm text-danger">No libraries are configured for your campus.</p>
        </Control>
        <Control id="edit-manuscript-abstract" required col class="sm:col-span-2">
          <Label>Abstract</Label>
          <Textarea v-model="form.description" :rows="8" :disabled="loading" :error="fieldErrors?.description?.[0]" />
        </Control>
        <Control id="edit-manuscript-doi" nullable col class="sm:col-span-2">
          <Label>DOI</Label>
          <Input v-model="form.doi" :max="255" :disabled="loading" :error="fieldErrors?.doi?.[0]" />
        </Control>
        <Control id="edit-manuscript-keywords" required col class="sm:col-span-2">
          <Label>Keywords</Label>
          <AddKeywords v-model="form" :disabled="loading" subtitle="Update keywords or subjects for your manuscript." />
          <p v-if="fieldErrors?.keywords?.[0]" class="text-sm text-danger">{{ fieldErrors.keywords[0] }}</p>
          <div class="mt-3 flex flex-wrap gap-2">
            <p v-if="!form.keywords?.length" class="text-sm text-muted-foreground">No keywords yet.</p>
            <Chip v-for="keyword in form.keywords" :key="keyword">{{ keyword }}</Chip>
          </div>
        </Control>
        <Control id="edit-manuscript-file" required col class="sm:col-span-2">
          <Label>Thesis File (PDF)</Label>
          <InputFile v-model="form.electronic_file" accept=".pdf" :disabled="loading" :error="fieldErrors?.electronic_file?.[0]" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button :disabled="loading" @click="close">Cancel</Button>
      <Button form="edit-manuscript-form" type="submit" variant="success" :loading="loading">Save & Resubmit</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import AddKeywords from '@/app/librarian/collections/catalog/forms/sections/modals/AddKeywords.vue'
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ data: AcaRepo }>()
const emit = defineEmits(['refresh'])

const acarepo = acaRepoStore()
const auth = authStore()
const options = optionStore()
const pop = usePopup()
const modal = ref<InstanceType<typeof Modal>>()

const loading = ref(false)
const error = ref<string | null>(null)
const fieldErrors = ref<Record<string, string[]> | null>(null)
const form = ref<UpdateAcaRepo>(createForm())

const campusLibraries = computed(() => options.libraries?.filter((library) => String(library.campus_id) === String(auth.user?.campus_id)) ?? [])

function createForm(): UpdateAcaRepo {
  return {
    id: props.data.id,
    title: props.data.title,
    category: props.data.category,
    description: props.data.description,
    doi: props.data.doi ?? '',
    electronic_file: props.data.electronic_file,
    keywords: [...(props.data.keywords ?? [])],
    library_id: props.data.library_id,
    language_id: props.data.language_id,
  }
}

function open() {
  form.value = createForm()
  error.value = null
  fieldErrors.value = null
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  error.value = null
  fieldErrors.value = null

  if (!form.value.keywords?.length) {
    await pop.info('Please enter at least one keyword.')
    return
  }

  if (!campusLibraries.value.some((library) => String(library.id) === String(form.value.library_id))) {
    fieldErrors.value = { library_id: ['Select a library that belongs to your campus.'] }
    return
  }

  const confirm = await pop.confirm({ text: 'Save your changes and resubmit this manuscript for review?' })
  if (!confirm.isConfirmed) return

  loading.value = true
  pop.load()
  try {
    const res = await acarepo.update(form.value)
    pop.success(res.message)
    close()
    emit('refresh')
  } catch (e: any) {
    fieldErrors.value = e?.response?.data?.errors ?? null
    error.value = e?.response?.data?.message ?? 'Unable to resubmit your manuscript. Please check your information and try again.'
  } finally {
    pop.unload()
    loading.value = false
  }
}

onBeforeMount(async () => {
  loading.value = true
  try {
    await Promise.all([
      options.fetch('languages', true),
      options.fetch('libraries', true),
    ])
  } catch (e: any) {
    error.value = e?.response?.data?.message ?? 'Unable to load submission options.'
  } finally {
    loading.value = false
  }
})
</script>
