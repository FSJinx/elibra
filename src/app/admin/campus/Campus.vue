<template>
  <!-- Page Heading -->
  <div class="space-y-1 shrink-0">
    <p class="text-primary text-md uppercase tracking-wide font-medium text-shadow-md text-shadow-primary/15">Good day, {{ auth.user?.first_name }}! 👋</p>

    <h1 class="text-2xl font-semibold">
      {{ $route.meta.title ?? 'Untitled' }}
    </h1>

    <p class="text-sm text-foreground-secondary">
      {{ $route.meta.description ?? `This is your today's preview for ${$route.meta.title}.` }}
    </p>
  </div>

  <!-- Actions -->
  <div class="flex w-full flex-wrap items-center justify-end gap-1.5 mb-3 mt-5">
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
        <th>Address</th>
        <th>Status</th>
      </tr>
    </Thead>

    <Tbody :loading="loading" :columns="5" :data="campuses">
      <!-- Results -->
      <tr v-for="c in campuses" class="cursor-pointer hover:bg-default/50" @click="viewCampus(c)">
        <Td :data="c.id" />

        <Td class="text-left" :data="c.name" />

        <Td :data="c.code" />

        <Td :data="c.address ?? 'No address'" />

        <Td>
          <Badge :variant="parse.status(c.status)">
            {{ parse.toCapital(c.status) }}
          </Badge>
        </Td>
      </tr>
    </Tbody>
  </Table>

  <!-- Create Modal -->
  <CreateNewCampusModal ref="createCampus" position="top" />
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
      params: {
        query: filters.query || undefined,
        status: filters.status || undefined,
        page: filters.page,
      },
    })

    campuses.value = res.data.data
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
  if (store.campuses) {
    campuses.value = store.campuses
  } else {
    await fetchCampuses()
  }
}

/*
|--------------------------------------------------------------------------
| Campus Actions
|--------------------------------------------------------------------------
*/

const viewCampus = (campus: any) => {
  console.log('View campus:', campus)

  // Later:
  // router.push({
  //   name: 'admin.campus.show',
  //   params: {
  //     id: campus.id,
  //   },
  // })
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
