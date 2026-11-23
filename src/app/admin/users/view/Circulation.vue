<template>
  <Table title="Circulation History" subtitle="Items borrowed by this user" :data="pagination">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th>Accession</Th>
        <Th class="text-left">Title</Th>
        <Th>Loan Mode</Th>
        <Th>Borrowed</Th>
        <Th>Due</Th>
        <Th>Status</Th>
      </tr>
    </Thead>
    <Tbody :data="circulation.data" :loading="loading">
      <Tr v-for="(item, index) in circulation.data" :key="item.id">
        <Td :data="(pagination?.from ?? 1) + index" />
        <Td :data="item.accession?.accession_number" />
        <Td class="text-left" :data="item.accession?.item?.title" />
        <Td :data="item.loan_mode?.name" />
        <Td :data="parse.formatDateTime(item.borrowed_at)" />
        <Td :data="parse.formatDateTime(item.due_at)" />
        <Td><Status :variant="item.status">{{ item.status }}</Status></Td>
      </Tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
const users = userStore()
const circulation = circulationStore()
const parse = useParser()
const pagination = ref<Pagination>()
const loading = ref(false)

async function fetch() {
  const patronId = users.currentData?.patron?.id
  if (!patronId) {
    circulation.data = []
    return
  }
  loading.value = true
  try {
    const res = await circulation.fetch({ patron_id: patronId })
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watch(() => users.currentData?.patron?.id, fetch, { immediate: true })
</script>
