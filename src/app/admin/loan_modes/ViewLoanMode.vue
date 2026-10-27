<template>
  <div class="size-full flex flex-col overflow-hidden">
    <div class="flex items-center p-4 bg-background border-b border-border">
      <Button variant="text" icon="arrow-left" data-title="Return" @click="router.back()" />

      <!-- e.g.   Loan Policy for: In House -->
      <Title :level="3">Loan Policy for: {{ loan_mode.currentData?.name ?? '-' }}</Title>
    </div>

    <div class="flex-1 flex flex-col overflow-y-auto p-5">
      <Table title="Patrons Loan Policy" subtitle="These are the current loan modes your library accomodate" :data="paginatedData" cols="5">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Patron Type</Th>
            <Th>Can Reserve</Th>
            <Th>Reservation Limit</Th>  
            <Th>
              Loan Period
              <p class="text-sm text-muted-foreground font-normal lowercase">(in days)</p>
            </Th>
            <Th>Max Loanable Item</Th>
            <Th>Max Renewals</Th>
            <Th>Fine Per Due</Th>
            <Th>Grace Period</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>

        <Tbody :data="policy.data" :loading="loading">
          <Tr v-for="(item, index) in policy.data" :key="index">
            <Td :data="index + 1" />
            <Td>
              <Button class="hover:text-info" size="sm">Edit</Button>
              <Button class="hover:text-danger" size="sm">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">

const policy = patronTypeLoanPolicyStore()
const loan_mode = loanModeStore()
const route = useRoute()
const router = useRouter()
const pop = usePopup()

const paginatedData = ref<Pagination>()
const loading = ref<boolean>(false)

onBeforeMount(async () => {
  pop.load()
  try {
    const id = String(route.params.id)
    if (String(loan_mode.currentData?.id) !== id) {
      await loan_mode.show(id)
    }
  } finally {
    pop.unload()
  }
})
</script>

<style scoped></style>
