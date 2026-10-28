<template>
  <Button variant="primary" @click="open" icon="plus-lg">New Section</Button>

  <Modal ref="modal" size="normal">
    <ModalHeader use-default-layout title="New Section" subtitle="Add new section to this section" icon="building-add" />

    <ModalBody>
      <Form id="create-section" class="flex flex-col gap-2 p-5" @submit="create">
        <Control id="section-name" required col>
          <Label>Name</Label>
          <Input type="text" placeholder="e.g. General Section" v-model="form.name" :error="errors?.name?.[0]" />
        </Control>
        <Control v-if="isAdmin && availableLibraries.length > 0" id="section-library" required col>
          <Label>Library</Label>
          <Select v-model="form.library_id" :error="errors?.library_id?.[0]">
            <Option value="" disabled>Select a library</Option>
            <Option v-for="library in availableLibraries" :key="library.id" :value="library.id">
              {{ library.name }}
            </Option>
          </Select>
        </Control>
        <p v-if="isAdmin && !form.library_id && availableLibraries.length === 0" class="text-sm text-warning">
          No libraries are available for your campus.
        </p>
        <Control id="section-head" nullable col>
          <Label>Section Head</Label>
          <Select v-model="form.librarian_id" :error="errors?.librarian_id?.[0]" :disabled="loadingLibrarians || !form.library_id">
            <Option value="">Use library head by default</Option>
            <Option v-for="librarian in librarians" :key="librarian.id" :value="librarian.id">
              {{ librarianName(librarian) }}
            </Option>
          </Select>
          <p v-if="form.library_id && !loadingLibrarians && librarians.length === 0" class="text-sm text-warning">
            No librarians are assigned to this library.
          </p>
        </Control>
      </Form>
    </ModalBody>

    <ModalFooter class="gap-2">
      <Button form="create-section" type="submit" variant="success">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()
const section = sectionStore()
const pop = usePopup()
const auth = authStore()
const emit = defineEmits(['refresh'])
const errors = ref<Record<string, string[]> | null>(null)
const librarians = ref<Librarian[]>([])
const availableLibraries = ref<Library[]>([])
const loadingLibrarians = ref(false)
const isAdmin = computed(() => auth.user?.role === 'admin' || auth.user?.role === 'super_admin')

const form = reactive({
  name: '',
  librarian_id: '',
  library_id: auth.user?.librarian?.library_id ?? auth.user?.library?.id ?? '',
})

function librarianName(librarian: Librarian): string {
  const user = librarian.user
  return user ? `${user.first_name} ${user.last_name}`.trim() : `Librarian #${librarian.id}`
}

async function loadLibrarians(libraryId: string | number) {
  librarians.value = []
  if (!libraryId) return

  loadingLibrarians.value = true
  try {
    const response = await get<{ data: Librarian[] }>('librarian', { library_id: libraryId })
    librarians.value = response.data
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to load librarians for this library.')
  } finally {
    loadingLibrarians.value = false
  }
}

watch(() => form.library_id, (libraryId) => loadLibrarians(libraryId), { immediate: true })

async function open() {
  form.name = ''
  form.librarian_id = ''
  errors.value = null

  if (isAdmin.value && !auth.user?.librarian?.library_id && !auth.user?.library?.id && availableLibraries.value.length === 0) {
    try {
      const response = await get<{ data: Library[] }>('library')
      availableLibraries.value = response.data.filter((library) => String(library.campus_id) === String(auth.user?.campus_id))
    } catch (error: any) {
      await pop.error(error?.response?.data?.message ?? 'Unable to load libraries for your campus.')
      return
    }
  }

  if (!form.library_id && availableLibraries.value.length === 1) {
    form.library_id = availableLibraries.value[0].id
  }

  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function create() {
  errors.value = null
  form.name = form.name.trim()
  if (!form.name) {
    errors.value = { name: ['Section name is required.'] }
    return
  }

  const confirm = await pop.confirm({ text: 'Are you sure to add this section to your library?' })

  if (confirm.isConfirmed) {
    pop.load()

    try {
      const res = await section.create(form)
      close()
      await pop.success(res.message ?? 'Added successfully')
      return emit('refresh')
    } catch (e: any) {
      errors.value = e?.response?.data?.errors ?? null
      if (!errors.value) {
        await pop.error(e?.response?.data?.message ?? 'Unable to create the section.')
      }
    } finally {
      pop.unload()
    }
  }
}
</script>

<style scoped></style>
