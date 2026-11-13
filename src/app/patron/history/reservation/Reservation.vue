<template>
  <main class="flex flex-col size-full gap-5 max-w-7xl mx-auto py-5 px-5 sm:px-0">
    <Form class="grid sm:grid-cols-[1fr_2fr] items-center gap-2" id="search-borrowing" @submit="search">
      <div class="flex items-center gap-2">
        <Button icon="arrow-left" variant="text" @click="router.back()">Return</Button>
      </div>
      <div class="flex items-center justify-end flex-wrap gap-2">
        <Button type="submit">Refresh</Button>
      </div>
    </Form>

    <Table title="Reservations" subtitle="Preview all your items you preserved" :data="pagination">
      <Thead>
        <tr>
          <Th>No</Th>
          <Th>Accession</Th>
          <Th class="text-left">Title</Th>
          <Th>Date Borrowed</Th>
          <Th>Status</Th>
        </tr>
      </Thead>
      <Tbody :data="holding.data" :loading>
        <Tr v-for="(holding, index) in holding.data" :key="index">
          <Td :data="(pagination?.from ?? 0) + index" />
          <Td :data="holding.accession?.accession_number" />
          <Td class="text-left" :data="holding.accession?.item?.title ?? null" />
          <Td :data="parse.formatDateTime(holding.created_at)" />
          <Td>
            <Status :variant="holding.picked_up_at ? 'success' : 'info'">
              {{ holding.picked_up_at ? 'Completed' : 'Awaiting pick-up' }}
            </Status>
          </Td>
        </Tr>
      </Tbody>
      <template #footer />
    </Table>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
const holding = holdingStore()
const parse = useParser()
const options = optionStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = reactive<Partial<HoldingParams>>({
  patron_id: auth.user?.patron?.id ?? undefined,
})

async function search() {
  loading.value = true
  try {
    const res = await holding.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watch(() => params, search, { deep: true, immediate: true })
</script>

<style scoped></style>
