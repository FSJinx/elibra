<template>
  <div class="flex-1 flex flex-col gap-5 overflow-hidden p-5">
    <div class="flex items-center gap-2 justify-end">
      <AddSectionModal v-if="library.currentData" :library-id="library.currentData.id" />
    </div>

    <Table title="Library Sections" subtitle="These are the sections / departments of this library" :data="pagination">
      <template #header>
        <Form class="flex items-center justify-end gap-2 w-full" id="search-sections" @submit="search">
          <Input id="search-section" v-model="params.query" class="max-w-100" placeholder="Search section by section name" enable-clear />
          <Button type="submit">Search</Button>
        </Form>
      </template>
      <Thead>
        <tr>
          <Th>No</Th>
          <Th class="text-left">Name</Th>
          <Th>Section Head</Th>
          <Th>Actions</Th>
        </tr>
      </Thead>
      <Tbody :data="sections.data" :loading="loading">
        <Tr v-for="(section, index) in sections.data" :key="section.id">
          <Td>{{ index + 1 }}</Td>
          <Td class="text-left">{{ section.name }} <Status variant="danger" v-if="section.deleted_at">Deleted</Status></Td>
          <Td :data="section.librarian?.user?.name?.full ?? 'None assigned'" />
          <Td class="space-x-2">
            <EditSectionModal :data="section" @refresh="search" />
            <Button size="sm" class="hover:text-danger" @click.stop="remove(section)">Delete</Button>
            <Button size="sm" class="hover:text-restore" @click.stop="restore(section)" v-if="section.deleted_at">Restore</Button>
          </Td>
        </Tr>
      </Tbody>
      <template #footer />
    </Table>
  </div>
</template>

<script setup lang="ts">
import AddSectionModal from '@/app/admin/libraries/modals/AddSectionModal.vue'
import EditSectionModal from '@/app/admin/libraries/modals/EditSectionModal.vue'
import RecentlyDeletedSectionsModal from '@/app/admin/libraries/modals/RecentlyDeletedSectionsModal.vue'

const sections = sectionStore()
const library = libraryStore()
const pop = usePopup()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = ref<Partial<SectionParams>>({
  query: '',
  library_id: library.currentData?.id ?? null,
})

async function search() {
  loading.value = true
  try {
    const res = await sections.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function remove(section: Section) {
  const confirmation = await pop.confirm({ text: `Are you sure you want to delete ${section.name}?` })
  if (confirmation.isConfirmed) {
    pop.load()
    try {
      const res = await sections.destroy(section)
      search()
      pop.success(res.message ?? 'Section deleted successfully')
    } catch (error: any) {
      pop.error(error?.response?.data?.message ?? 'Unable to delete the section. Please try again.')
    }
  }
}

async function restore(section: Section) {
  const confirmation = await pop.confirm({ text: `Are you sure you want to restore ${section.name}?` })
  if (confirmation.isConfirmed) {
    pop.load()
    try {
      const res = await sections.restore(section)
      search()
      pop.success(res.message ?? 'Section deleted successfully')
    } catch (error: any) {
      pop.error(error?.response?.data?.message ?? 'Unable to delete the section. Please try again.')
    }
  }
}

watchDebounced(() => params.value.query, search, { debounce: 300, immediate: true })
onBeforeUnmount(() => (sections.data = null))
</script>

<style scoped></style>
