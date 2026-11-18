<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Check In" description="Manage active borrowings and patron returns" icon="bag-dash"></SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3 overflow-y-auto">
      <Table title="Active Borrowings" subtitle="Patron groups, with the latest borrowing patron shown first" :data="pagination" @page="params.page = $event" @per_page="params.per_page = $event">
        <template #header>
          <Form class="flex-1 flex items-center justify-end gap-2" id="search-circulation" @submit="search">
            <Input class="max-w-150" id="circulation-query" type="text" placeholder="Search by accession number, item title, or borrower's name..." v-model="params.query" />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left">Patron</Th>
            <Th>Patron Type</Th>
            <Th>Active Items</Th>
            <Th>Latest Borrowed</Th>
            <Th>Latest Due</Th>
            <Th>Overdue</Th>
            <Th>Fine Charged</Th>
            <Th>Action</Th>
          </tr>
        </Thead>
        <Tbody :data="groups" :loading="loading" error-message="No active borrowings yet, lend an item to start managing borrowings.">
          <Tr v-for="(group, index) in groups" :key="group.patron_id">
            <Td :data="index + 1" />
            <Td class="text-left" align="left">
              <p class="font-medium">{{ group.patron?.user?.name?.full }}</p>
              <p class="text-xs text-foreground-secondary">{{ group.patron?.user?.username }}</p>
            </Td>
            <Td :data="group.patron?.patron_type?.name ?? 'Unknown'" />
            <Td :data="group.quantity" />
            <Td :data="parse.formatDateAgo(group.latest_borrowed_at)" />
            <Td :data="parse.formatDate(group.latest_due_at)" />
            <Td>
              <Status :variant="group.overdue_quantity > 0 ? 'danger' : 'success'">
                {{ group.overdue_quantity > 0 ? `${group.overdue_quantity} overdue` : 'None' }}
              </Status>
            </Td>
            <Td :data="parse.toMoney(group.fine_charged)" />
            <Td>
              <Button size="sm" left-icon="clock-history" @click="openHistory(group.patron)">View History</Button>
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
      <PatronHistoryModal ref="historyModal" :patron="historyPatron" />
    </div>
  </div>
</template>

<script setup lang="ts">
import PatronHistoryModal from '@/app/librarian/circulation/check_in/PatronHistoryModal.vue'

interface PatronCirculationGroup {
  patron_id: any
  patron: Patron | null
  quantity: number
  latest_borrowed_at: string
  latest_due_at: string
  overdue_quantity: number
  fine_charged: number
  loans: Circulation[]
}

const circulation = circulationStore()
const parse = useParser()
const auth = authStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const historyModal = ref<InstanceType<typeof PatronHistoryModal>>()
const historyPatron = ref<Patron | null>(null)
const groups = computed(() => (circulation.data ?? []) as unknown as PatronCirculationGroup[])
const params = ref<Partial<CirculationParams>>({
  query: '',
  library_id: auth.user?.library?.id,
  status: 'active',
  group_by: 'patron',
})

function openHistory(patron: Patron | null) {
  historyPatron.value = patron
  nextTick(() => historyModal.value?.open())
}

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
