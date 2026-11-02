<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Check In" description="Manage active borrowings and patron returns" icon="bag-dash"></SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3 overflow-y-auto">
      <Card class="">
        <CardBody class="flex items-center gap-2">
          <Form class="flex-1 flex items-center gap-2" id="search-circulation" @submit="search">
            <Input id="circulation-query" type="text" placeholder="Search by accession number, item title, or borrower's name..." v-model="params.query" />
          </Form>
        </CardBody>
      </Card>
      
      <Table title="Active Borrowings" subtitle="List of all active borrowings in your library" :data="pagination" @page="params.page = $event" @per_page="params.per_page = $event">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession Number</Th>
            <Th>Title</Th>
            <Th>Loan Mode</Th>
            <Th>Borrowed By</Th>
            <Th>Date Borrowed</Th>
            <Th>Due Date</Th>
            <Th>Date Returned</Th>
            <Th>Renewal Count</Th>
            <Th>Issued By</Th>
            <Th>Status</Th>
            <Th>Action</Th>
          </tr>
        </Thead>
        <Tbody :data="circulation.data" :loading="loading" error-message="No active borrowings yet, lend an item to start managing borrowings.">
          <Tr v-for="(item, index) in circulation.data" :key="index">
            <Td :data="index + 1" />
            <Td :data="item.accession?.accession_number" />
            <Td :data="item.accession?.item?.title" />
            <Td :data="item.loan_mode?.name" />
            <Td :data="item.patron?.user?.name?.full" />
            <Td :data="item.borrowed_at" />
            <Td :data="item.due_at" />
            <Td :data="item.returned_at" />
            <Td :data="item.renewal_count" />
            <Td :data="item.processed_by?.user?.name?.full" />
            <Td :data="item.status" />
            <Td>
              <ReturnButton :data="item" />
              <RenewButton :data="item" />
              <DeleteButton :data="item" />
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import DeleteButton from '@/app/librarian/circulation/check_in/modals/DeleteButton.vue'
import RenewButton from '@/app/librarian/circulation/check_in/modals/RenewButton.vue'
import ReturnButton from '@/app/librarian/circulation/check_in/modals/ReturnButton.vue'

const circulation = circulationStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = ref<Partial<CirculationParams>>({
  query: '',
})

async function search() {
  loading.value = true
  try {
    const res = await circulation.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

onBeforeMount(search)
</script>

<style scoped></style>
