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
const store = useCampusStore()
const parse = useParser()
const { campuses, loading } = storeToRefs(store)

const createCampus = ref<InstanceType<typeof CreateNewCampusModal> | null>(null)

const filters = store.params

/*
|--------------------------------------------------------------------------
| Fetch Campuses
|--------------------------------------------------------------------------
*/

const fetchCampuses = async () => {
  return store.fetch(true)
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
  store.refresh()
}

/*
|--------------------------------------------------------------------------
| Refresh
|--------------------------------------------------------------------------
*/

const refresh = async () => {
  await store.refresh()
}

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
    await store.deleteCampus(campus)
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
