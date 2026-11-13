<template>
  <div class="flex flex-col size-full">
    <SectionHeader title="Campus" description="Manage campuses of the Isabela State University" icon="buildings">
      <div class="flex items-center justify-end gap-2">
        <AddNewCampusModal />
        <RecentlyDeletedCampusModal />
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-3 p-5">
      <Table title="Campus Table" subtitle="List of campuses in ISU" :data="pagination">
        <template #header>
          <Form class="flex items-center justify-end gap-2 w-full" id="search-campus" @submit="search">
            <Input id="search-query" class="max-w-100" placeholder="Search by campus name" />
            <Button @click="search">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <th>No.</th>
            <th class="text-left">Name</th>
            <th>Campus Code</th>
            <th>Status</th>
            <th>Last Modified</th>
          </tr>
        </Thead>
        <Tbody :data="campus.data" :loading="loading">
          <Tr :to="{ name: 'admin.campus.show', params: { id: c.id } }" v-for="(c, index) in campus.data" :key="index">
            <Td :data="index + 1" />
            <Td class="text-left" :data="c?.name" />
            <Td :data="c?.code" />
            <Td>
              <Status :variant="c?.status">{{ c?.status }}</Status>
            </Td>
            <Td :data="parse.formatDateAgo(c?.updated_at)" />
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import AddNewCampusModal from '@/app/admin/campus/modals/AddNewCampusModal.vue'
import RecentlyDeletedCampusModal from '@/app/admin/campus/modals/RecentlyDeletedCampusModal.vue'
import UpdateCampusModal from '@/app/admin/campus/modals/UpdateCampusModal.vue'

const campus = campusStore()
const parse = useParser()

const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const defaultParams = (): Partial<CampusParams> => ({
  query: '',
})
const params = reactive(defaultParams())

async function search() {
  loading.value = true
  try {
    const res = await campus.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}
onBeforeMount(search)
</script>

<style scoped></style>
