<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Check In" description="Manage active borrowings and patron returns" icon="bag-dash"></SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3 overflow-y-auto">
      <Table title="Active Borrowings" subtitle="List of all active borrowings in your library" :data="pagination" @page="params.page = $event" @per_page="params.per_page = $event">
        <template #header>
          <Form class="flex-1 flex items-center justify-end gap-2" id="search-circulation" @submit="search">
            <Input class="max-w-150" id="circulation-query" type="text" placeholder="Search by accession number, item title, or borrower's name..." v-model="params.query" />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession Number</Th>
            <Th class="text-left">Title</Th>
            <Th>Loan Mode</Th>
            <Th>Borrowed By</Th>
            <Th>Date Borrowed</Th>
            <Th>Due Date</Th>
            <Th>Status</Th>
            <Th>Fine Charged</Th>
            <Th>Action</Th>
          </tr>
        </Thead>
        <Tbody :data="circulation.data" :loading="loading" error-message="No active borrowings yet, lend an item to start managing borrowings.">
          <Tr v-for="(item, index) in circulation.data" :key="item.id">
            <Td :data="index + 1" />
            <Td :data="item.accession?.accession_number" />
            <Td :class="[{ 'text-danger!': Number.parseFloat(item.fine_charged) > 0 }]" class="text-left" :data="item.accession?.item?.title" />
            <Td :data="item.loan_mode?.name" />
            <Td :data="item.patron?.user?.name?.full" />
            <Td :data="parse.formatDateAgo(item.borrowed_at)" />
            <Td :data="parse.formatDate(item.due_at)" />
            <Td>
              <Status :variant="item.status">{{ item.status }}</Status>
            </Td>
            <Td class="space-x-2">
              <ReturnButton :data="item" @refresh="search" v-if="['borrowed', 'overdue'].includes(item.status)" />
              <RenewButton :data="item" @refresh="search" v-if="item.status === 'borrowed'" />
              <DeleteButton :data="item" @refresh="search" />
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
import { formatDateTime } from '@/utils/date'

const circulation = circulationStore()
const parse = useParser()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = ref<Partial<CirculationParams>>({
  query: '',
})

async function search() {
  if (loading.value) return

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
