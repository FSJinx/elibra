<template>
  <div class="flex-1 flex flex-col gap-5 overflow-hidden p-5">
    <Card>
      <CardBody class="flex items-center gap-2">
        <Input id="search-section" v-model="search" class="max-w-100" placeholder="Search section by section name" />

        <div class="ml-auto flex items-center gap-2">
          <RecentlyDeletedSectionsModal v-if="library.currentData" :library-id="library.currentData.id" />
          <AddSectionModal v-if="library.currentData" :library-id="library.currentData.id" />
        </div>
      </CardBody>
    </Card>

    <Table title="Library Sections" subtitle="These are the sections / departments of this library" :data-length="filteredSections.length">
      <Thead>
        <tr>
          <Th>No</Th>
          <Th>Name</Th>
          <Th>Actions</Th>
        </tr>
      </Thead>
      <Tbody :data="filteredSections" :loading="sections.loading" cols="3">
        <Tr v-for="(section, index) in filteredSections" :key="section.id">
          <Td>{{ index + 1 }}</Td>
          <Td>{{ section.name }}</Td>
          <Td class="space-x-2">
            <EditSectionModal :data="section" />
            <Button size="sm" class="hover:text-danger" @click="remove(section)">Delete</Button>
          </Td>
        </Tr>
      </Tbody>
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
const search = ref('')
const filteredSections = computed(() => {
  const query = search.value.trim().toLocaleLowerCase()
  return (sections.data ?? []).filter((section) => section.name.toLocaleLowerCase().includes(query))
})

async function remove(section: Section) {
  const confirmation = await pop.confirm({ text: `Are you sure you want to delete ${section.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const res = await sections.destroy(section)
    pop.unload()
    await pop.success(res.message ?? 'Section deleted successfully')
  } catch (error: any) {
    pop.unload()
    await pop.error(error?.response?.data?.message ?? 'Unable to delete the section. Please try again.')
  }
}

onBeforeMount(async () => {
  try {
    await sections.fetch(library.currentData?.id, true)
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to load sections. Please try again.')
  }
})
</script>

<style scoped></style>
