<template>
  <div class="flex flex-col h-full w-full">
    <!-- Actions -->
    <div class="flex w-full flex-wrap items-center justify-end gap-1.5 p-5">
      <!-- Search -->
      <form class="min-w-85" @submit.prevent>
        <Input id="searchQuery" v-model="filters.query" enable-clear left-icon="search" placeholder="Search by name, code, address" />
      </form>

      <!-- Status Filter -->
      <Select class="max-w-max" id="status" v-model="filters.status" title="statuses">
        <Option value="">All Status</Option>
        <Option value="active">Active</Option>
        <Option value="inactive">Inactive</Option>
      </Select>

      <!-- Refresh -->
      <Button variant="info" icon="arrow-clockwise" data-title="Reset & Refresh" :loading="loading" @click="refresh" />

      <!-- Create -->
      <Button variant="primary" data-title="Add new record" @click="createCampus?.open"> Create New </Button>
    </div>

    <!-- Campus Table -->
    <Table>
      <Thead>
        <tr>
          <th class="w-25">ID</th>
          <th class="text-left">Campus Name</th>
          <th>Code</th>
          <th>Status</th>
          <!-- <th>Actions</th> -->
        </tr>
      </Thead>

      <Tbody :loading="loading" :columns="4" :data="campuses">
        <!-- Results -->
        <tr v-for="c in campuses" class="cursor-pointer hover:bg-default/50" @click="viewCampus(c)">
          <Td :data="c.id" />

          <Td class="text-left" :data="c.name" />

          <Td :data="c.code" />

          <Td>
            <Status :variant="parse.status(c.status)">
              {{ parse.toCapital(c.status) }}
            </Status>
          </Td>

          <!-- <Td>
            <div class="flex justify-center gap-1" @click.stop>
              <Button size="sm" class="hover:shadow hover:shadow-info/50" @click="createCampus?.open(c)">Edit</Button>
              <Button size="sm" class="hover:shadow hover:shadow-danger/50" @click="deleteCampus(c)">Delete</Button>
            </div>
          </Td> -->
        </tr>
      </Tbody>
    </Table>

    <!-- Create Modal -->
    <CreateNewCampusModal ref="createCampus" position="top" @created="fetchCampuses" />
  </div>
</template>

<script setup lang="ts">
import CreateNewCampusModal from '@/app/admin/campus/CreateNewCampusModal.vue'

const auth = authStore()
const store = campusStore()
const parse = useParser()

const createCampus = ref<InstanceType<typeof CreateNewCampusModal> | null>(null)

const campuses = ref<Campus[] | null>(null)
const loading = ref(false)

const filters = reactive({
  query: '',
  sort: '',
  order: '',
  status: '',
  page: '',
})

/*
|--------------------------------------------------------------------------
| Fetch Campuses
|--------------------------------------------------------------------------
*/

const fetchCampuses = async () => {
  loading.value = true

  try {
    const res = await api.get('campus/get', {
      params: filters,
    })

    campuses.value = res.data.data
    store.setCampuses(campuses.value)
  } catch (error) {
    console.error('Failed to fetch campuses:', error)
  } finally {
    loading.value = false
  }
}

/*
|--------------------------------------------------------------------------
| Filter State
|--------------------------------------------------------------------------
*/

const hasFilters = computed(() => {
  return Boolean(filters.query.trim() || filters.status)
})

const resetFilters = () => {
  filters.query = ''
  filters.status = ''
}

/*
|--------------------------------------------------------------------------
| Refresh
|--------------------------------------------------------------------------
*/

const refresh = async () => {
  await fetchCampuses()
}

watchDebounced(
  filters,
  (val) => {
    fetchCampuses()
  },
  { deep: true, debounce: 300 },
)

/*
|--------------------------------------------------------------------------
| Campus Actions
|--------------------------------------------------------------------------
*/

const viewCampus = (campus: any) => {
  router.push({
    name: 'admin.campus.show',
    params: {
      id: campus.id,
      name: campus.name,
      code: campus.code,
    },
  })
}

const deleteCampus = async (campus: Campus) => {
  if (!window.confirm(`Delete ${campus.name}? This may affect its departments and branches.`)) return

  try {
    await api.delete(`campus/delete/${campus.id}`)
    await fetchCampuses()
  } catch (error) {
    console.error('Failed to delete campus:', error)
  }
}

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {
  refresh()
})
</script>

<style scoped></style>
