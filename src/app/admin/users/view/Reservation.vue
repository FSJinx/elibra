<template>
  <Table title="Reservations" subtitle="Reservations made by this user" :data="pagination">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th>Accession</Th>
        <Th class="text-left">Title</Th>
        <Th>Pickup Date</Th>
        <Th>Status</Th>
      </tr>
    </Thead>
    <Tbody :data="holding.data" :loading="loading">
      <Tr v-for="(item, index) in holding.data" :key="item.id">
        <Td :data="(pagination?.from ?? 1) + index" />
        <Td :data="item.accession?.accession_number" />
        <Td class="text-left" :data="item.accession?.item?.title" />
        <Td :data="item.pickup_date ? parse.formatDateTime(item.pickup_date) : '—'" />
        <Td><Status :variant="item.status">{{ item.status }}</Status></Td>
      </Tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
const users = userStore()
const holding = holdingStore()
const parse = useParser()
const pagination = ref<Pagination>()
const loading = ref(false)

async function fetch() {
  const patronId = users.currentData?.patron?.id
  if (!patronId) {
    holding.data = []
    return
  }
  loading.value = true
  try {
    const res = await holding.fetch({ patron_id: patronId })
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watch(() => users.currentData?.patron?.id, fetch, { immediate: true })
</script>
