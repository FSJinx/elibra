<template>
  <div class="size-full flex flex-col gap-5 overflow-hidden">
    <SectionHeader title="Sections" description="Manage sections or departments of this library." icon="building-add">
      <div class="flex items-center justify-end gap-2">
        <NewSection @refresh="search" />
      </div>
    </SectionHeader>

    <div class="flex flex-col flex-1 p-5 gap-3 overflow-y-auto">
      <Table title="Library Sections" subtitle="These are all the sections or departments under this library" :data="data">
        <template #header>
          <Form id="section-form" class="flex-1 flex items-center justify-end gap-2" @submit="search">
            <Input id="section-query" class="max-w-100" type="text" placeholder="Search by section name" v-model="params.query" />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <Th class="text-left">Name</Th>
            <Th>Section Head</Th>
            <Th>Date Added</Th>
            <Th>Last Modified</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="data" :loading="loading" cols="5">
          <Tr v-for="(section, index) in sections.data" :key="index">
            <Td class="text-left" :data="section.name" />
            <Td :data="section.librarian?.user.name.full ?? 'None'" />
            <Td :data="parse.formatDate(section.created_at)" />
            <Td :data="parse.formatTimeAgo(section.updated_at)" />
            <Td class="space-x-2">
              <EditSection :data="section" />
              <Button size="sm" class="hover:text-danger">Delete</Button>
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

const sections = sectionStore()
const auth = authStore()
const pop = usePopup()

const loading = ref<boolean>(false)
const parse = useParser()
const data = ref<Pagination>()

const params = reactive<Partial<SectionParams>>({
  query: '',
  library_id: auth.user?.librarian?.library_id ?? null,
})

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

watchDebounced(() => params.query, search, { debounce: 300 })
onBeforeMount(() => search())
</script>

<style scoped></style>
