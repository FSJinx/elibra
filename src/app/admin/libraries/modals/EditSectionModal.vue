<template>
  <Button size="sm" class="hover:text-warning" @click.stop="open">Edit</Button>

  <Modal ref="modal" size="normal" :has-inputs="hasChanges">
    <ModalHeader use-default-layout title="Edit Section" :subtitle="`Update ${data.name}`" icon="diagram-3" />
    <ModalBody>
      <Form :id="formId" class="flex flex-col gap-5 p-5" @submit="submit">
        <Control :id="`section-name-${data.id}`" col required>
          <Label>Name</Label>
          <Input v-model="form.name" placeholder="e.g. Reference" :error="errors?.name?.[0]" />
        </Control>
        <Control :id="`section-head-${data.id}`" col>
          <Label>Section Head</Label>
          <Select
            :id="`section-head-select-${data.id}`"
            v-model="form.librarian_id"
            :error="errors?.librarian_id?.[0]"
            :disabled="loadingLibrarians"
            placeholder="Unassigned"
          >
            <Option value="">Unassigned</Option>
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
      <Button v-if="hasChanges" type="submit" variant="info" :form="formId">Update</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Form from '@/components/form/Form.vue'
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ data: Section }>()
const sections = sectionStore()
const pop = usePopup()
const modal = ref<InstanceType<typeof Modal>>()
const emit = defineEmits(['refresh'])
const original = reactive<{ name: string; librarian_id: number | string | null }>({
  name: props.data.name,
  librarian_id: props.data.librarian_id ?? '',
})
const form = reactive<{ name: string; librarian_id: number | string | null }>({
  name: props.data.name,
  librarian_id: props.data.librarian_id ?? '',
})
const errors = ref<Record<string, string[]> | null>(null)
const librarians = ref<Librarian[]>([])
const loadingLibrarians = ref(false)
const formId = computed(() => `update-section-${props.data.id}`)
const hasChanges = computed(
  () => form.name !== original.name || String(form.librarian_id ?? '') !== String(original.librarian_id ?? ''),
)

function librarianName(librarian: Librarian): string {
  const user = librarian.user
  return user ? `${user.first_name} ${user.last_name}`.trim() : `Librarian #${librarian.id}`
}

async function open() {
  original.name = props.data.name
  original.librarian_id = props.data.librarian_id ?? ''
  form.name = original.name
  form.librarian_id = original.librarian_id
  errors.value = null

  if (librarians.value.length === 0) {
    loadingLibrarians.value = true
    try {
      const response = await get<Librarian[] | { data?: Librarian[] }>('librarian', { library_id: props.data.library_id })
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

  const confirmation = await pop.confirm({ text: `Are you sure you want to update ${props.data.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const res = await sections.update({
      ...props.data,
      name: form.name,
      librarian_id: form.librarian_id || null,
    })
    pop.unload()
    original.name = res.data.name
    original.librarian_id = res.data.librarian_id ?? ''
    form.name = original.name
    form.librarian_id = original.librarian_id
    errors.value = null
    await nextTick()
    modal.value?.close()
    emit('refresh')
    await pop.success(res.message ?? 'Section updated successfully')
  } catch (error: any) {
    pop.unload()
    errors.value = error?.response?.data?.errors ?? null
    if (!errors.value?.name) {
      await pop.error(error?.response?.data?.message ?? 'Unable to update the section. Please try again.')
    }
  }
}

async function close() {
  if (hasChanges.value) {
    const confirmation = await pop.confirm({ text: 'You have unsaved changes. Are you sure you want to close this?' })
    if (!confirmation.isConfirmed) return
  }

  form.name = original.name
  form.librarian_id = original.librarian_id
  errors.value = null
  await nextTick()
  modal.value?.close()
}
</script>

<style scoped></style>
