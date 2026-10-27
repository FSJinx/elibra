<template>
  <div class="size-full flex flex-col gap-5 overflow-hidden">
    <SectionHeader title="Sections" description="Manage sections or departments of this library." icon="building-add">
      <div class="flex items-center justify-end gap-2">
        <NewSection @refresh="search" />
      </div>
    </SectionHeader>

    <div class="flex flex-col flex-1 p-5 gap-3 overflow-y-auto">
      <Card class="flex items-center flex-row gap-2">
        <Form id="section-form" class="flex-1 flex items-center gap-2" @submit="search">
          <Input id="section-query" class="max-w-100" type="text" placeholder="Search by section name" v-model="params.query" />
          <Button type="submit">Search</Button>
        </Form>
<<<<<<< HEAD
        <Button variant="restore" left-icon="filter">Filter</Button>
      </Card>

      <Table title="Library Sections" subtitle="These are all the sections or departments under this library" :data-length="filteredSections.length">
=======
      </Card>

      <Table title="Library Sections" subtitle="These are all the sections or departments under this library" :data="data">
>>>>>>> fdc30dd79379f9f6f4b9e803464f825da5df199b
        <Thead>
          <tr>
            <Th class="text-left">Name</Th>
            <Th class="text-left">Section Head</Th>
            <Th>Date Added</Th>
            <Th>Last Modified</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
<<<<<<< HEAD
        <Tbody :data="filteredSections" :loading="loading" cols="5">
          <Tr v-for="(d, index) in filteredSections" :key="d.id">
=======
        <Tbody :data="data" :loading="loading" cols="5">
          <Tr v-for="(d, index) in sections.data" :key="index">
>>>>>>> fdc30dd79379f9f6f4b9e803464f825da5df199b
            <Td class="text-left" :data="d.name" />
            <Td class="text-left" :data="sectionHeadName(d)" />
            <Td :data="parse.formatDate(d.created_at)" />
            <Td :data="parse.formatTimeAgo(d.updated_at)" />
            <Td class="space-x-2">
<<<<<<< HEAD
              <EditSectionModal v-if="canManageSections" :data="d" @refresh="search" />
              <Button v-if="canManageSections" size="sm" class="hover:text-danger" @click.stop="remove(d)">Delete</Button>
=======
              <EditSection :data="d" />
              <Button size="sm" class="hover:text-danger">Delete</Button>
>>>>>>> fdc30dd79379f9f6f4b9e803464f825da5df199b
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import EditSection from '@/app/librarian/administration/sections/modals/EditSection.vue'
import NewSection from '@/app/librarian/administration/sections/modals/NewSection.vue'
import EditSectionModal from '@/app/admin/libraries/modals/EditSectionModal.vue'

const sections = sectionStore()
const auth = authStore()
const pop = usePopup()

const loading = ref<boolean>(false)
const parse = useParser()
<<<<<<< HEAD
const data = ref<Section[] | null>()
const params = reactive({ ...sections.defaultParams })
const filteredSections = computed(() => {
  const query = params.query.trim().toLocaleLowerCase()
  return (data.value ?? []).filter((section) => section.name.toLocaleLowerCase().includes(query))
})
const canManageSections = computed(() => ['admin', 'super_admin'].includes(auth.user?.role ?? ''))

function sectionHeadName(section: Section): string {
  const user = section.librarian?.user
  return user ? `${user.first_name} ${user.last_name}`.trim() : 'Unassigned'
}
=======
const data = ref<Pagination>()

const params = reactive(sections.defaultParams)
>>>>>>> fdc30dd79379f9f6f4b9e803464f825da5df199b

async function search() {
  loading.value = true

  try {
    const res = await sections.fetch(params)
    data.value = res.data
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to load sections. Please try again.')
  } finally {
    loading.value = false
  }
}

<<<<<<< HEAD
async function remove(section: Section) {
  const confirmation = await pop.confirm({ text: `Are you sure you want to delete ${section.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const response = await sections.destroy(section)
    data.value = (data.value ?? []).filter((item) => item.id !== section.id)
    pop.unload()
    await pop.success(response.message ?? 'Section deleted successfully')
  } catch (error: any) {
    pop.unload()
    await pop.error(error?.response?.data?.message ?? 'Unable to delete the section. Please try again.')
  }
}

onBeforeMount(() => {
  params.library_id = auth.user?.librarian?.library_id ?? auth.user?.library?.id ?? ''
  search()
})
=======
watchDebounced(() => params.query, search, { debounce: 300 })
onBeforeMount(() => search())
>>>>>>> fdc30dd79379f9f6f4b9e803464f825da5df199b
</script>

<style scoped></style>
