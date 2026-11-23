<template>
  <Table title="Attendance History" subtitle="Library visits recorded for this user" :data="pagination">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th>Library</Th>
        <Th>Section</Th>
        <Th>Date</Th>
      </tr>
    </Thead>
    <Tbody :data="attendance.data" :loading="loading">
      <Tr v-for="(item, index) in attendance.data" :key="item.id">
        <Td :data="(pagination?.from ?? 1) + index" />
        <Td :data="item.library?.name" />
        <Td :data="item.section?.name" />
        <Td :data="parse.formatDateTime(item.created_at)" />
      </Tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
const users = userStore()
const attendance = attendanceStore()
const parse = useParser()
const pagination = ref<Pagination>()
const loading = ref(false)

async function fetch() {
  const patronId = users.currentData?.patron?.id
  if (!patronId) {
    attendance.data = []
    return
  }
  loading.value = true
  try {
    const res = await attendance.fetch({ patron_id: patronId })
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watch(() => users.currentData?.patron?.id, fetch, { immediate: true })
</script>
