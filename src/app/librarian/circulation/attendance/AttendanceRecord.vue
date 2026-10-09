<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Attendance" description="Record and review your library's visitor attendance" icon="person-check">
      <div class="flex items-center justify-end">
        <Button variant="primary" icon="plus" @click="showLogForm = !showLogForm">
          Log Attendance
        </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-3 overflow-hidden p-5">
      <Card v-if="showLogForm">
        <CardBody class="flex flex-col gap-4">
          <div>
            <h2 class="text-sm font-semibold">New attendance log</h2>
            <p class="mt-1 text-xs text-foreground-secondary">Record when a patron enters or leaves the library.</p>
          </div>

          <Form class="grid gap-3 md:grid-cols-[minmax(0,1fr)_180px_180px_auto]" @submit.prevent="addAttendance">
            <Input id="patron" v-model="newLog.patron" placeholder="Search patron or enter ID..." left-icon="person" />
            <Select id="attendance-action" title="Action" v-model="newLog.action">
              <Option value="in">Checked in</Option>
              <Option value="out">Checked out</Option>
            </Select>
            <Input id="attendance-time" v-model="newLog.time" type="time" />
            <Button type="submit" variant="success" :disabled="!newLog.patron.trim()">Save</Button>
          </Form>
        </CardBody>
      </Card>

      <Card>
        <CardBody class="flex flex-wrap items-center gap-2">
          <Input id="attendance-search" v-model="filters.search" type="text" class="max-w-100" placeholder="Search by patron..." enable-clear left-icon="search" />
          <Button type="button" variant="info">Search</Button>
          <Select id="attendance-action-filter" title="Action" class="max-w-max" v-model="filters.action">
            <Option value="">All actions</Option>
            <Option value="in">Checked in</Option>
            <Option value="out">Checked out</Option>
          </Select>
          <Input id="attendance-date-filter" v-model="filters.date" type="date" class="max-w-45" />
          <Button type="button" variant="danger" @click="resetFilters">Reset</Button>
        </CardBody>
      </Card>

      <div class="grid gap-3 sm:grid-cols-3">
        <Card v-for="stat in stats" :key="stat.label">
          <CardBody class="flex items-center gap-3">
            <span class="flex size-10 items-center justify-center rounded-lg" :class="stat.class">
              <Icon :icon="stat.icon" />
            </span>
            <div>
              <p class="text-xs text-foreground-secondary">{{ stat.label }}</p>
              <p class="text-xl font-semibold">{{ stat.value }}</p>
            </div>
          </CardBody>
        </Card>
      </div>

      <Table title="Attendance Logs" subtitle="These are your library's recent attendance records.">
        <Thead>
          <tr>
            <Th>No.</Th>
            <Th class="text-left">Patron</Th>
            <Th>Patron Type</Th>
            <Th>Action</Th>
            <Th>Date</Th>
            <Th>Time</Th>
            <Th>Recorded By</Th>
          </tr>
        </Thead>
        <Tbody :data="filteredLogs" :loading="false" cols="7">
          <tr v-for="(log, index) in filteredLogs" :key="log.id" class="hover">
            <Td :data="index + 1" />
            <Td class="text-left">
              <p class="font-medium">{{ log.patron }}</p>
              <p class="text-xs text-foreground-secondary">{{ log.patronId }}</p>
            </Td>
            <Td class="capitalize" :data="log.patronType" />
            <Td>
              <Status class="rounded-full border border-current/30 px-3 py-1 text-xs capitalize" :variant="log.action === 'in' ? 'success' : 'default'">
                {{ log.action === 'in' ? 'Checked in' : 'Checked out' }}
              </Status>
            </Td>
            <Td :data="log.date" />
            <Td :data="log.time" />
            <Td :data="log.recordedBy" />
          </tr>
          <tr v-if="filteredLogs.length === 0">
            <Td colspan="7" class="py-10 text-center text-sm text-foreground-secondary">No attendance records match your filters.</Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
interface AttendanceLog {
  id: number
  patron: string
  patronId: string
  patronType: string
  action: 'in' | 'out'
  date: string
  time: string
  recordedBy: string
}

const showLogForm = ref(false)
const filters = reactive({ search: '', action: '', date: '' })
const newLog = reactive({ patron: '', action: 'in' as 'in' | 'out', time: '08:00' })

const logs = ref<AttendanceLog[]>([
  { id: 1, patron: 'Maria Santos', patronId: '2023-10421', patronType: 'student', action: 'in', date: 'Oct 09, 2026', time: '08:14 AM', recordedBy: 'J. Reyes' },
  { id: 2, patron: 'Juan Dela Cruz', patronId: '2022-08321', patronType: 'faculty', action: 'in', date: 'Oct 09, 2026', time: '08:06 AM', recordedBy: 'J. Reyes' },
  { id: 3, patron: 'Ana Lopez', patronId: '2024-01192', patronType: 'student', action: 'out', date: 'Oct 09, 2026', time: '07:52 AM', recordedBy: 'A. Garcia' },
  { id: 4, patron: 'Pedro Reyes', patronId: '2021-07318', patronType: 'staff', action: 'in', date: 'Oct 08, 2026', time: '04:35 PM', recordedBy: 'A. Garcia' },
  { id: 5, patron: 'Sofia Cruz', patronId: '2025-00481', patronType: 'student', action: 'out', date: 'Oct 08, 2026', time: '03:48 PM', recordedBy: 'J. Reyes' },
])

const filteredLogs = computed(() => logs.value.filter((log) => {
  const search = filters.search.trim().toLowerCase()
  const matchesSearch = !search || log.patron.toLowerCase().includes(search) || log.patronId.toLowerCase().includes(search)
  const matchesAction = !filters.action || log.action === filters.action
  const matchesDate = !filters.date || log.date === new Date(`${filters.date}T00:00:00`).toLocaleDateString('en-US', { month: 'short', day: '2-digit', year: 'numeric' })

  return matchesSearch && matchesAction && matchesDate
}))

const stats = computed(() => [
  { label: 'Total records', value: filteredLogs.value.length, icon: 'clipboard-list', class: 'bg-sky-50 text-sky-600' },
  { label: 'Checked in today', value: logs.value.filter((log) => log.date === 'Oct 09, 2026' && log.action === 'in').length, icon: 'log-in', class: 'bg-emerald-50 text-emerald-600' },
  { label: 'Checked out today', value: logs.value.filter((log) => log.date === 'Oct 09, 2026' && log.action === 'out').length, icon: 'log-out', class: 'bg-violet-50 text-violet-600' },
])

function addAttendance() {
  logs.value.unshift({
    id: Date.now(),
    patron: newLog.patron.trim(),
    patronId: 'NEW-00000',
    patronType: 'student',
    action: newLog.action,
    date: 'Oct 09, 2026',
    time: newLog.time,
    recordedBy: 'Current user',
  })
  newLog.patron = ''
  showLogForm.value = false
}

function resetFilters() {
  filters.search = ''
  filters.action = ''
  filters.date = ''
}
</script>

<style scoped></style>
