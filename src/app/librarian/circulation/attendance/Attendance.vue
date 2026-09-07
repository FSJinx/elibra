<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Attendance" description="Track patron visits and library gate logs." icon="clipboard-check" class="bg-background border-b border-border">
      <div class="flex items-end justify-end">
        <Button variant="primary" icon="plus" @click="openNewVisit"> Log visit </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-5 p-5">
      <!-- Stats -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Visits today" :value="stats.visitsToday" icon="users" variant="default" />
        <StatCard label="Currently in library" :value="stats.currentlyIn" icon="door-open" variant="success" />
        <StatCard label="Avg. duration" :value="stats.avgDuration" icon="clock" variant="warning" />
        <StatCard label="This week" :value="stats.thisWeek" icon="calendar" variant="default" />
      </div>

      <!-- Filters -->
      <Card>
        <Form>
          <template #body>
            <Control>
              <Input id="attendance-search" placeholder="Search by patron name or ID..." v-model="filters.search" enable-clear />
              <Select id="attendance-status" title="Status" v-model="filters.status">
                <Option value="">All statuses</Option>
                <Option value="in">In library</Option>
                <Option value="out">Checked out</Option>
              </Select>
              <Select id="attendance-branch" title="Branch" v-model="filters.branch">
                <Option value="">All branches</Option>
                <template v-for="item in branch.branches" :key="item.id">
                  <Option :value="item.id">{{ item.name }}</Option>
                </template>
              </Select>
              <Select id="attendance-purpose" title="Purpose" v-model="filters.purpose">
                <Option value="">All purposes</Option>
                <Option value="study">Study</Option>
                <Option value="research">Research</Option>
                <Option value="borrowing">Borrowing</Option>
                <Option value="internet">Internet use</Option>
                <Option value="other">Other</Option>
              </Select>
              <Button @click="resetFilters">Reset</Button>
            </Control>
          </template>
        </Form>
      </Card>

      <Table title="Attendance Log" subtitle="This is today's recent attendance logs.">
        <Thead>
          <tr>
            <th class="text-left">Patron</th>
            <th>Branch</th>
            <th>Purpose</th>
            <th>Time in</th>
            <th>Time out</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </Thead>
        <Tbody :data="filteredVisits.length" :loading="false" :cols="8">
          <tr v-for="row in filteredVisits" :key="row.id">
            <Td class="text-left">
              <p class="font-medium">{{ row.patron_name }}</p>
              <p class="text-xs text-foreground-secondary">{{ row.patron_id }}</p>
            </Td>

            <Td class="text-foreground-secondary">{{ row.branch }}</Td>

            <Td class="text-foreground-secondary capitalize">{{ row.purpose }}</Td>

            <Td class="text-foreground-secondary">{{ formatTime(row.time_in) }}</Td>

            <Td class="text-foreground-secondary">{{ row.time_out ? formatTime(row.time_out) : '—' }}</Td>

            <Td class="text-foreground-secondary">{{ formatDuration(row) }}</Td>

            <Td class="text-center">
              <Status class="text-xs capitalize px-3 py-1 rounded-full border border-current/30" :variant="statusVariant(visitStatus(row))">
                {{ visitStatus(row) === 'in' ? 'In library' : 'Checked out' }}
              </Status>
            </Td>

            <Td>
              <Button v-if="visitStatus(row) === 'in'" size="sm" variant="primary" @click="logTimeOut(row)"> Log time-out </Button>
            </Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
interface AttendanceRecord {
  id: number
  patron_name: string
  patron_id: string
  branch: string
  purpose: 'study' | 'research' | 'borrowing' | 'internet' | 'other'
  time_in: string
  time_out: string | null
}

const branch = branchStore()

// TODO: replace with real fetch (e.g. useAttendance() composable)
const visits = reactive<AttendanceRecord[]>([
  { id: 1, patron_name: 'Juan Dela Cruz', patron_id: 'STU-00231', branch: 'Main Branch', purpose: 'study', time_in: '2026-09-06T08:15:00', time_out: '2026-09-06T10:40:00' },
  { id: 2, patron_name: 'Maria Santos', patron_id: 'STU-00456', branch: 'Annex Branch', purpose: 'research', time_in: '2026-09-06T09:05:00', time_out: null },
  { id: 3, patron_name: 'Pedro Reyes', patron_id: 'STU-00789', branch: 'Main Branch', purpose: 'borrowing', time_in: '2026-09-06T09:30:00', time_out: '2026-09-06T09:50:00' },
  { id: 4, patron_name: 'Ana Lopez', patron_id: 'STU-00912', branch: 'Main Branch', purpose: 'internet', time_in: '2026-09-06T10:00:00', time_out: null },
])

const filters = reactive({
  search: '',
  status: '',
  branch: '',
  purpose: '',
})

function visitStatus(row: AttendanceRecord): 'in' | 'out' {
  return row.time_out ? 'out' : 'in'
}

function statusVariant(status: string) {
  const map: Record<string, string> = {
    in: 'success',
    out: 'default',
  }
  return map[status] ?? 'default'
}

function formatTime(datetime: string) {
  return new Date(datetime).toLocaleTimeString('en-PH', { hour: 'numeric', minute: '2-digit' })
}

function formatDuration(row: AttendanceRecord) {
  if (!row.time_out) return '—'
  const minutes = Math.round((new Date(row.time_out).getTime() - new Date(row.time_in).getTime()) / 60000)
  const hrs = Math.floor(minutes / 60)
  const mins = minutes % 60
  return hrs > 0 ? `${hrs}h ${mins}m` : `${mins}m`
}

const filteredVisits = computed(() =>
  visits.filter((row) => {
    const matchesSearch = !filters.search || row.patron_name.toLowerCase().includes(filters.search.toLowerCase()) || row.patron_id.toLowerCase().includes(filters.search.toLowerCase())

    const matchesStatus = !filters.status || visitStatus(row) === filters.status
    const matchesBranch = !filters.branch || row.branch === filters.branch
    const matchesPurpose = !filters.purpose || row.purpose === filters.purpose

    return matchesSearch && matchesStatus && matchesBranch && matchesPurpose
  }),
)

const stats = computed(() => {
  const today = new Date().toDateString()
  const todayVisits = visits.filter((v) => new Date(v.time_in).toDateString() === today)

  const completed = visits.filter((v) => v.time_out)
  const avgMinutes = completed.length ? Math.round(completed.reduce((sum, v) => sum + (new Date(v.time_out!).getTime() - new Date(v.time_in).getTime()) / 60000, 0) / completed.length) : 0

  const weekAgo = Date.now() - 7 * 86400000

  return {
    visitsToday: todayVisits.length,
    currentlyIn: visits.filter((v) => visitStatus(v) === 'in').length,
    avgDuration: avgMinutes > 0 ? `${avgMinutes}m` : '—',
    thisWeek: visits.filter((v) => new Date(v.time_in).getTime() >= weekAgo).length,
  }
})

function logTimeOut(row: AttendanceRecord) {
  // TODO: call backend, e.g. await attendanceStore.logTimeOut(row.id)
  row.time_out = new Date().toISOString()
}

function resetFilters() {
  filters.search = ''
  filters.status = ''
  filters.branch = ''
  filters.purpose = ''
}

function openNewVisit() {
  // TODO: open modal/drawer for manual visit entry (patron search + purpose)
}
</script>
