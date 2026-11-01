<template>
  <Button variant="primary" left-icon="plus-lg" @click="open">New Section</Button>

  <Modal ref="modal" size="normal" :has-inputs="hasInput">
    <ModalHeader use-default-layout title="New Section" subtitle="Add a section to the library" icon="diagram-3" />
    <ModalBody>
      <Form id="new-section-form" class="flex flex-col gap-5 p-5" @submit="submit">
        <Control id="section-name" col required>
          <Label>Name</Label>
          <Input v-model="form.name" placeholder="e.g. Reference" :error="errors?.name?.[0]" />
        </Control>
        <Control id="section-head" col>
          <Label>Section Head</Label>
          <Select
            id="section-head-select"
            v-model="form.librarian_id"
            :error="errors?.librarian_id?.[0]"
            :disabled="loadingLibrarians"
            placeholder="Use library head by default"
          >
            <Option value="">Use library head by default</Option>
            <Option v-for="librarian in librarians" :key="librarian.id" :value="librarian.id">
              {{ librarianName(librarian) }}
            </Option>
          </Select>
          <p v-if="!loadingLibrarians && librarians.length === 0" class="text-sm text-warning">
            No librarians are assigned to this library yet.
          </p>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button type="submit" variant="success" form="new-section-form">Create Section</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Form from '@/components/form/Form.vue'
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ libraryId: number }>()
const sections = sectionStore()
const pop = usePopup()
const modal = ref<InstanceType<typeof Modal>>()
const form = reactive<{ name: string; librarian_id: number | string | null }>({ name: '', librarian_id: '' })
const errors = ref<Record<string, string[]> | null>(null)
const librarians = ref<Librarian[]>([])
const loadingLibrarians = ref(false)
const hasInput = computed(() => form.name.length > 0 || Boolean(form.librarian_id))

function librarianName(librarian: Librarian): string {
  const user = librarian.user
  return user ? `${user.first_name} ${user.last_name}`.trim() : `Librarian #${librarian.id}`
}

async function open() {
  form.name = ''
  form.librarian_id = ''
  errors.value = null

  if (librarians.value.length === 0) {
    loadingLibrarians.value = true
    try {
      const response = await get<Librarian[] | { data?: Librarian[] }>('librarian', { library_id: props.libraryId })
      const librarianData = Array.isArray(response) ? response : response.data
      if (!Array.isArray(librarianData)) {
        throw new Error('The librarian list response was invalid.')
      }
      librarians.value = librarianData
    } catch (error: any) {
      await pop.error(error?.response?.data?.message ?? 'Unable to load librarians for this library.')
      return
    } finally {
      loadingLibrarians.value = false
    }
  }

  modal.value?.open()
}

async function submit() {
  form.name = form.name.trim()
  errors.value = null
  if (!form.name) {
    errors.value = { name: ['Section name is required'] }
    return
  }

  const confirmation = await pop.confirm({ text: `Are you sure you want to add ${form.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const res = await sections.create({
      name: form.name,
      library_id: props.libraryId,
      librarian_id: form.librarian_id || null,
    })
    pop.unload()
    form.name = ''
    form.librarian_id = ''
    errors.value = null
    await nextTick()
    modal.value?.close()
    await pop.success(res.message ?? 'Section created successfully')
  } catch (error: any) {
    pop.unload()
    errors.value = error?.response?.data?.errors ?? null
    if (!errors.value?.name) {
      await pop.error(error?.response?.data?.message ?? 'Unable to create the section. Please try again.')
    }
  }
}

async function close() {
  if (hasInput.value) {
    const confirmation = await pop.confirm({ text: 'You have unsaved changes. Are you sure you want to close this?' })
    if (!confirmation.isConfirmed) return
  }

  form.name = ''
  errors.value = null
  await nextTick()
  modal.value?.close()
}
</script>

<style scoped></style>
