<template>
  <div class="flex flex-col size-full overflow-hidden">
    <SectionHeader title="Libraries" description="Manage libraries in the Isabela State University" icon="building">
      <div class="flex items center justify-end">
        <AddNewLibraryModal />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-3 p-5">
      <Table title="Library Table" subtitle="List of libraries in ISU" :data="pagination">
        <template #header>
          <Form class="w-full flex items-center justify-end gap-2" id="search-library" @submit="search">
            <Input id="search-library" class="max-w-100" placeholder="Search by library name" />
            <Button @click="search">Refresh</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Campus</th>
            <th>Last Modified</th>
            <th>Actions</th>
          </tr>
        </Thead>
        <Tbody :data="library.data" :loading="loading">
          <Tr :to="{ name: 'admin.libraries.view', params: { id: item.id } }" v-for="(item, index) in library.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="item?.name" />
            <Td :data="item.email ?? null"></Td>
            <Td :data="item?.phone" />
            <Td :data="item.campus?.name ?? null" />
            <Td :data="parse.formatDateAgo(item?.updated_at)" />
            <Td class="space-x-2">
              <EditLibraryModal :data="item" />
              <Button size="sm" class="hover:text-danger!" @click="remove(item)">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewLibraryModal from '@/app/admin/libraries/modals/AddNewLibraryModal.vue'
import EditLibraryModal from '@/app/admin/libraries/modals/EditLibraryModal.vue'

const library = libraryStore()
const campus = campusStore()
const parse = useParser()
const pop = usePopup()

const loading = ref<boolean>(false)
const pagination = ref<Pagination>()

async function search() {
  loading.value = true
  try {
    const res = await library.fetch()
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function remove(c: Library) {
  const res = await pop.confirm({ text: `Are you sure you want to delete ${c?.name}?` })

  if (res.isConfirmed) {
    pop.load()
    try {
      const res = await library.remove(c)
      pop.success(res.message ?? 'Something is deleted')
    } catch (e) {
      await pop.error((e as any)?.response?.data?.message ?? 'Unable to delete the library. Please try again.')
    }
  }
}

onBeforeMount(search)
</script>

<style scoped></style>
