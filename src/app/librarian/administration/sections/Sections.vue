<template>
  <div class="size-full flex flex-col gap-5 overflow-hidden">
    <SectionHeader title="Library Sections" description="Manage sections or departments of this library." icon="building-add">
      <div class="flex items-center justify-end gap-2">
        <NewSection @refresh="search" />
      </div>
    </SectionHeader>

    <div class="flex flex-col flex-1 p-5 gap-3">
      <Card class="flex items-center flex-row gap-2">
        <Form id="section-form" class="flex-1 flex items-center gap-2" @submit="search">
          <Input id="section-query" class="max-w-100" type="text" placeholder="Search by section name" v-model="params.query" />
          <Button type="submit" variant="info">Search</Button>
        </Form>

        <Button variant="restore" left-icon="filter">Filter</Button>
      </Card>

      <Table title="Library Sections" subtitle="These are all the sections or departments under this library" data-length="">
        <Thead>
          <tr>
            <Th class="text-left">Name</Th>
            <Th class="text-left">Section Head</Th>
            <Th>Date Addede</Th>
            <Th>Last Modified</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="data" :loading="loading" cols="5">
          <Tr v-for="(d, index) in data" :key="index">
            <Td class="text-left" :data="d.name" />
            <Td class="text-left" :data="d.librarian_id ? d.librarian?.name : null" />
            <Td :data="parse.formatDate(d.created_at)" />
            <Td :data="parse.formatTimeAgo(d.updated_at)" />
            <Td class="space-x-2">
              <Button size="sm" class="hover:text-info">Edit</Button>
              <Button size="sm" class="hover:text-danger">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import NewSection from '@/app/librarian/administration/sections/modals/NewSection.vue'

const sections = sectionStore()

const loading = ref<boolean>(false)
const parse = useParser()
const data = ref<Section[] | null>()
const params = reactive(sections.defaultParams)

async function search() {
  loading.value = true

  try {
    console.log('Search')

    const res = await sections.fetch(params)
    data.value = res.data
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
  }
}

onBeforeMount(() => search())
</script>

<style scoped></style>
