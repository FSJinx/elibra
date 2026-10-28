<template>
  <div class="size-full flex flex-col overflow-hidden">
    <div class="flex items-center p-4 bg-background border-b border-border">
      <Button variant="text" icon="arrow-left" data-title="Return" @click="router.back()" />

      <Title :level="3">Loan Mode: {{ loan_mode.currentData?.name ?? '-' }}</Title>
    </div>

    <div class="flex-1 flex flex-col overflow-y-auto p-5 gap-4">
      <Card class="items-end">
        <NewPatronLoanPolicy @refresh="fetch" />
      </Card>
      <Table title="Patrons Loan Policy" subtitle="These are the current loan modes your library accomodate" :data="paginatedData" cols="5">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Patron Type</Th>
            <Th>
              Loan Period
              <p class="text-sm text-muted-foreground font-normal lowercase">(in days)</p>
            </Th>
            <Th>Max Loanable Item</Th>
            <Th>Max Renewals</Th>
            <Th>Fine Per Due</Th>
            <Th>Grace Period</Th>
            <Th class="text-left">Notes</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>

        <Tbody :data="policy.data" :loading="loading">
          <Tr v-for="(item, index) in policy.data" :key="index">
            <Td :data="index + 1" />
            <Td :data="item.patron_type?.name" />
            <Td :data="item.loan_period_days" />
            <Td :data="item.max_items" />
            <Td :data="item.max_renewals" />
            <Td :data="item.fine_per_due" />
            <Td :data="item.grace_period" />
            <Td class="text-left line-clamp-1" :data="item.notes" />
            <Td>
              <EditLoanPolicy :data="item" @refresh="fetch" />
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import EditLoanPolicy from '@/app/admin/loan_modes/modals/EditLoanPolicy.vue'
import NewPatronLoanPolicy from '@/app/admin/loan_modes/modals/NewPatronLoanPolicy.vue'

const policy = patronTypeLoanPolicyStore()
const loan_mode = loanModeStore()
const route = useRoute()
const pop = usePopup()
const crumb = useBreadcrumbStore()

const paginatedData = ref<Pagination>()
const loading = ref<boolean>(false)

async function fetch() {
  loading.value = true
  try {
    paginatedData.value = (await policy.fetch({ loan_mode_id: loan_mode.currentData?.id })).data
  } finally {
    loading.value = false
  }
}

async function load() {
  loading.value = true
  pop.load()
  try {
    await loan_mode.show({ id: route.params.id })
  } finally {
    pop.unload()
    fetch()
  }
}
watchEffect(() => loan_mode.currentData?.name && crumb.set(`${route.name as string}:${route.params.id}`, loan_mode.currentData.name))
onBeforeMount(load)
onBeforeUnmount(() => {
  policy.data = null
  loan_mode.currentData = null
})
</script>

<style scoped></style>
