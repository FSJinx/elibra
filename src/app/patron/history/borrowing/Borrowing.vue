<template>
  <main class="flex flex-col size-full gap-5 max-w-7xl mx-auto py-5 px-5 sm:px-0">
    <Form class="grid sm:grid-cols-2 items-center gap-2" id="search-borrowing" @submit="search">
      <div class="flex items-center gap-2">
        <Button icon="arrow-left" variant="text" @click="router.back()" >Return</Button>
      </div>
      <div class="flex items-center justify-end gap-2">
        <Button type="submit">Refresh</Button>
      </div>
    </Form>
    <Table title="Borrowing History" subtitle="Preview all the items you borrowed" :data="pagination">
      <Thead>
        <tr>
          <Th>No</Th>
          <Th>Accession</Th>
          <Th class="text-left">Title</Th>
          <Th>Loan Mode</Th>
          <Th>Date Borrowed</Th>
          <Th>Status</Th>
        </tr>
      </Thead>
      <Tbody :data="circulation.data" :loading>
        <Tr v-for="(circulation, index) in circulation.data" :key="index">
          <Td :data="pagination?.from ?? 0 + index" />
          <Td :data="circulation.accession?.accession_number" />
          <Td class="text-left" :data="circulation.accession?.item?.title ?? null" />
          <Td :data="circulation.loan_mode?.name" />
          <Td :data="parse.formatDateTime(circulation.borrowed_at)" />
          <Td>
            <Status :variant="circulation.status">{{ circulation.status }}</Status>
          </Td>
        </Tr>
      </Tbody>
      <template #footer />
    </Table>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
const circulation = circulationStore()
const parse = useParser()
const options = optionStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = reactive<Partial<CirculationParams>>({
  patron_id: auth.user?.patron?.id ?? undefined,
})

async function search() {
  loading.value = true
  try {
    const res = await circulation.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watch(() => params, search, { deep: true, immediate: true })
</script>

<style scoped></style>
