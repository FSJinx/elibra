<template>
  <main class="flex flex-col size-full gap-5 max-w-7xl mx-auto py-5 px-5 sm:px-0">
    <Form class="grid sm:grid-cols-[1fr_2fr] items-center gap-2" id="search-borrowing" @submit="search">
      <div class="flex items-center gap-2">
        <Button icon="arrow-left" variant="text" @click="router.back()">Return</Button>
      </div>
      <div class="flex items-center justify-end flex-wrap gap-2">
        <Select class="max-w-max" v-model="params.campus_id" title="Campuses">
          <Option value="" label="All Campus" />
          <Option v-for="campus in options.campuses" :value="campus.id" :label="campus.name" />
        </Select>
        <Select class="max-w-max" v-model="params.library_id" title="Libraries">
          <Option value="" label="All Libraries" />
          <Option v-for="library in options.libraries?.filter((i) => i.campus_id === params.campus_id)" :value="library.id" :label="library.name" />
        </Select>
        <Select class="max-w-max" v-model="params.section_id" title="Sections">
          <Option value="" label="All Sections" />
          <Option v-for="section in options.sections?.filter((i) => i.library_id === params.library_id)" :value="section.id" :label="section.name" />
        </Select>
        <Button type="submit">Refresh</Button>
      </div>
    </Form>

    <Table title="Attendance History" subtitle="Preview all your library tracks" :data="pagination">
      <Thead>
        <tr>
          <Th>No</Th>
          <Th>Library</Th>
          <Th>Section</Th>
          <Th>Date</Th>
        </tr>
      </Thead>
      <Tbody :data="attendance.data" :loading>
        <Tr v-for="(attendance, index) in attendance.data" :key="index">
          <Td :data="pagination?.from ?? 0 + index" />
          <Td :data="attendance.library?.name ?? null" />
          <Td :data="attendance.section?.name ?? null" />
          <Td :data="parse.formatDateTime(attendance.created_at)" />
        </Tr>
      </Tbody>
      <template #footer />
    </Table>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
const attendance = attendanceStore()
const parse = useParser()
const options = optionStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = reactive<Partial<AttendanceParams>>({
  patron_id: auth.user?.patron?.id ?? undefined,
  campus_id: auth.user?.campus_id ?? null,
})

async function search() {
  loading.value = true
  try {
    const res = await attendance.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watch(() => params, search, { deep: true, immediate: true })
</script>

<style scoped></style>
