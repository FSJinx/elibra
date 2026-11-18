<template>
  <header class="shrink-0 p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
      <div>
        <p class="mb-1.5 text-xs font-semibold uppercase tracking-widest text-primary">System Administration</p>
        <h1 class="text-2xl font-bold tracking-tight text-foreground">Good day, {{ auth.user?.first_name }}!</h1>
        <p class="mt-1 text-sm text-foreground-secondary">System-wide library operations and resource overview.</p>
      </div>
      <div class="flex items-center gap-2 text-sm text-foreground-secondary">
        <Icon icon="calendar" />
        <time>{{ clock.today }}</time>
      </div>
    </div>
  </header>

  <main class="min-h-full bg-slate-50 p-5">
    <div class="mx-auto space-y-6">
      <section>
        <div class="mb-3 flex items-end justify-between">
          <Title :level="4" class="uppercase tracking-wider text-primary">System at a glance</Title>
          <span class="text-xs text-slate-500">{{ loading ? 'Refreshing live records...' : snapshotLabel }}</span>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
          <StatCard label="Campuses" :value="metrics.campuses" icon="building" variant="primary" :loading="loading" />
          <StatCard label="Libraries" :value="metrics.libraries" icon="book" variant="success" :loading="loading" />
          <StatCard label="Library staff" :value="metrics.librarians" icon="person-workspace" variant="info" :loading="loading" />
          <StatCard label="Patrons" :value="metrics.patrons" icon="people" variant="restore" :loading="loading" />
          <StatCard label="All users" :value="metrics.users" icon="person" variant="warning" :loading="loading" />
          <StatCard label="Catalog items" :value="metrics.catalog_items" icon="journals" variant="primary" :loading="loading" />
          <StatCard label="Physical copies" :value="metrics.copies" icon="collection" variant="success" :loading="loading" />
          <StatCard label="Available copies" :value="metrics.available_copies" icon="check-circle" variant="success" :loading="loading" />
          <StatCard label="Active loans" :value="metrics.active_loans" icon="arrow-left-right" variant="warning" :loading="loading" />
          <StatCard label="Overdue loans" :value="metrics.overdue_loans" icon="clock-history" variant="danger" :loading="loading" />
          <StatCard label="Borrowed today" :value="metrics.borrowed_today" icon="box-arrow-in-right" variant="info" :loading="loading" />
          <StatCard label="Returned today" :value="metrics.returned_today" icon="box-arrow-left" variant="success" :loading="loading" />
          <StatCard label="Total circulations" :value="metrics.total_circulations" icon="arrow-repeat" variant="primary" :loading="loading" />
          <StatCard label="Renewals" :value="metrics.renewals" icon="repeat" variant="restore" :loading="loading" />
          <StatCard label="Pending reservations" :value="metrics.pending_holdings" icon="bookmark" variant="warning" :loading="loading" />
          <StatCard label="Active inventories" :value="metrics.active_inventories" icon="clipboard-check" variant="info" :loading="loading" />
          <StatCard label="Fine balance" :value="currency(metrics.fine_balance)" icon="cash" variant="danger" :loading="loading" />
        </div>
      </section>

      <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_380px]">
        <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
              <h2 class="font-semibold text-slate-900">System circulation activity</h2>
              <p class="mt-1 text-sm text-slate-500">Borrowed and returned records over the last 30 days</p>
            </div>
            <div class="flex gap-3 text-xs text-slate-500">
              <span class="flex items-center gap-1"><i class="size-2 rounded-full bg-primary"></i>Borrowed</span>
              <span class="flex items-center gap-1"><i class="size-2 rounded-full bg-emerald-500"></i>Returned</span>
            </div>
          </div>
          <div class="h-80 p-5">
            <div v-if="loading" class="h-full animate-pulse rounded-lg bg-slate-100"></div>
            <div v-else class="flex h-full items-end gap-1 border-b border-slate-200">
              <div v-for="day in dailyActivity" :key="day.date" class="relative flex h-full min-w-0 flex-1 items-end justify-center gap-px" :title="`${day.label}: ${day.borrowed} borrowed, ${day.returned} returned`">
                <div class="w-[44%] rounded-t bg-primary/70" :style="{ height: `${barHeight(day.borrowed)}%` }"></div>
                <div class="w-[44%] rounded-t bg-emerald-500/70" :style="{ height: `${barHeight(day.returned)}%` }"></div>
              </div>
            </div>
            <div v-if="dailyActivity.length" class="mt-2 flex justify-between text-[10px] text-slate-400">
              <span>{{ dailyActivity[0]?.label }}</span>
              <span>{{ dailyActivity[Math.floor(dailyActivity.length / 2)]?.label }}</span>
              <span>{{ dailyActivity[dailyActivity.length - 1]?.label }}</span>
            </div>
          </div>
        </article>

        <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">User distribution</h2>
            <p class="mt-1 text-sm text-slate-500">System accounts by role</p>
          </div>
          <div class="p-5">
            <div v-if="loading" class="h-64 animate-pulse rounded-lg bg-slate-100"></div>
            <template v-else>
              <div class="mx-auto size-44 rounded-full" :style="{ background: rolePieGradient }"></div>
              <div class="mt-5 grid grid-cols-2 gap-3">
                <div v-for="row in roleBreakdown" :key="row.role" class="rounded-lg border border-slate-100 p-3">
                  <p class="text-xs capitalize text-slate-500">{{ roleLabel(row.role) }}</p>
                  <p class="mt-1 text-lg font-semibold text-slate-900">{{ Number(row.total).toLocaleString() }}</p>
                </div>
              </div>
              <p v-if="!roleBreakdown.length" class="py-12 text-center text-sm text-slate-500">No user records found.</p>
            </template>
          </div>
        </article>
      </section>

      <section class="grid gap-6 lg:grid-cols-2">
        <Table title="Campus network" subtitle="Campuses ranked by library count" :data="{ data: campusBreakdown, total: campusBreakdown.length } as Pagination">
          <Thead>
            <tr>
              <th class="text-left">Campus</th>
              <th>Libraries</th>
              <th>Users</th>
              <th>Items</th>
            </tr>
          </Thead>
          <Tbody :data="campusBreakdown" :loading="loading" cols="4">
            <Tr v-for="campus in campusBreakdown" :key="campus.id">
              <Td class="text-left" align="left">
                <p class="font-medium">{{ campus.name }}</p>
                <p class="text-xs text-slate-500">{{ campus.code ?? 'No code' }}</p>
              </Td>
              <Td :data="Number(campus.libraries).toLocaleString()" />
              <Td :data="Number(campus.users).toLocaleString()" />
              <Td :data="Number(campus.items).toLocaleString()" />
            </Tr>
          </Tbody>
        </Table>

        <Table title="Largest library collections" subtitle="Libraries ranked by catalog item count" :data="{ data: libraryBreakdown, total: libraryBreakdown.length } as Pagination">
          <Thead>
            <tr>
              <th class="text-left">Library</th>
              <th>Campus</th>
              <th>Items</th>
              <th>Staff</th>
            </tr>
          </Thead>
          <Tbody :data="libraryBreakdown" :loading="loading" cols="4">
            <Tr v-for="library in libraryBreakdown" :key="library.id">
              <Td class="text-left" :data="library.name" align="left" />
              <Td :data="library.campus ?? 'Unknown campus'" />
              <Td :data="Number(library.items).toLocaleString()" />
              <Td :data="Number(library.staff).toLocaleString()" />
            </Tr>
          </Tbody>
        </Table>
      </section>

      <section class="grid gap-6 lg:grid-cols-2 xl:grid-cols-4">
        <BreakdownCard title="Collection by item type" subtitle="Catalog categories" :rows="itemTypes" />
        <BreakdownCard title="Patrons by type" subtitle="Registered patron categories" :rows="patronTypes" />
        <BreakdownCard title="Circulation status" subtitle="All circulation records" :rows="circulationStatuses" status />
        <BreakdownCard title="Copy status" subtitle="Physical accession records" :rows="accessionStatuses" status />
      </section>

      <section class="grid gap-6 xl:grid-cols-[minmax(0,1.35fr)_minmax(340px,.65fr)]">
        <Table title="Recent circulation records" subtitle="Latest transactions across the entire system" :data="{ data: recentCirculations, total: recentCirculations.length } as Pagination">
          <Thead>
            <tr>
              <th class="text-left">Borrower</th>
              <th class="text-left">Title / Library</th>
              <th>Status</th>
              <th>Borrowed</th>
              <th>Due</th>
            </tr>
          </Thead>
          <Tbody :data="recentCirculations" :loading="loading" cols="5">
            <Tr v-for="loan in recentCirculations" :key="loan.id">
              <Td class="text-left" align="left">
                <p class="font-medium">{{ loan.borrower }}</p>
                <p class="text-xs text-slate-500">{{ loan.patron_type ?? 'Patron' }}</p>
              </Td>
              <Td class="text-left" align="left">
                <p class="max-w-72 truncate font-medium" :title="loan.title">{{ loan.title ?? 'Unknown title' }}</p>
                <p class="text-xs text-slate-500">{{ loan.library ?? 'Unknown library' }}</p>
              </Td>
              <Td><Status :variant="loan.status">{{ statusLabel(loan.status) }}</Status></Td>
              <Td :data="formatDate(loan.borrowed_at)" />
              <Td :data="formatDate(loan.due_at)" />
            </Tr>
          </Tbody>
        </Table>

        <Table title="Recent catalog additions" subtitle="Latest items system-wide" :data="{ data: recentItems, total: recentItems.length } as Pagination">
          <Thead>
            <tr>
              <th class="text-left">Title</th>
              <th>Added</th>
            </tr>
          </Thead>
          <Tbody :data="recentItems" :loading="loading" cols="2">
            <Tr v-for="item in recentItems" :key="item.id">
              <Td class="text-left" align="left">
                <p class="max-w-64 truncate font-medium" :title="item.title">{{ item.title }}</p>
                <p class="text-xs text-slate-500">{{ item.library ?? 'Unknown library' }} · {{ item.campus ?? 'Unknown campus' }}</p>
              </Td>
              <Td :data="formatDate(item.created_at)" />
            </Tr>
          </Tbody>
        </Table>
      </section>
    </div>
  </main>
</template>

<script setup lang="ts">
import BreakdownCard from '@/app/admin/dashboard/components/BreakdownCard.vue'
import type { Pagination } from '@/services/pagination'

const clock = useClock()
const auth = authStore()
const parse = useParser()

const loading = ref(true)
const summary = ref<any>(null)

const metrics = computed(() => summary.value?.metrics ?? {})
const dailyActivity = computed(() => summary.value?.daily_activity ?? [])
const roleBreakdown = computed(() => summary.value?.role_breakdown ?? [])
const campusBreakdown = computed(() => summary.value?.campus_breakdown ?? [])
const libraryBreakdown = computed(() => summary.value?.library_breakdown ?? [])
const itemTypes = computed(() => (summary.value?.item_types ?? []).map((row: any) => ({ label: row.name, total: row.total })))
const patronTypes = computed(() => (summary.value?.patron_types ?? []).map((row: any) => ({ label: row.name, total: row.total })))
const circulationStatuses = computed(() => (summary.value?.circulation_statuses ?? []).map((row: any) => ({ label: statusLabel(row.status), total: row.total })))
const accessionStatuses = computed(() => (summary.value?.accession_statuses ?? []).map((row: any) => ({ label: statusLabel(row.status), total: row.total })))
const recentCirculations = computed(() => summary.value?.recent_circulations ?? [])
const recentItems = computed(() => summary.value?.recent_items ?? [])

const snapshotLabel = computed(() => `Live snapshot · ${new Intl.DateTimeFormat('en-PH', { dateStyle: 'medium', timeStyle: 'short' }).format(new Date())}`)
const activityMax = computed(() => Math.max(1, ...dailyActivity.value.flatMap((day: any) => [Number(day.borrowed), Number(day.returned)])))

const roleColors = ['#2563eb', '#16a34a', '#7c3aed', '#d97706', '#dc2626']
const rolePieGradient = computed(() => {
  const total = roleBreakdown.value.reduce((sum: number, row: any) => sum + Number(row.total), 0)
  if (!total) return '#e2e8f0'

  let cursor = 0
  const stops = roleBreakdown.value.map((row: any, index: number) => {
    const start = cursor
    cursor += (Number(row.total) / total) * 100
    return `${roleColors[index % roleColors.length]} ${start}% ${cursor}%`
  })
  return `conic-gradient(${stops.join(', ')})`
})

function barHeight(value: number) {
  return Math.max(value ? 5 : 1, (Number(value) / activityMax.value) * 100)
}

function currency(value: number | undefined) {
  return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', maximumFractionDigits: 2 }).format(Number(value ?? 0))
}

function roleLabel(role: string) {
  return role === 'super_admin' ? 'Super Admin' : String(role ?? 'Unknown').replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase())
}

function statusLabel(status: string) {
  return String(status ?? '').replaceAll('_', ' ').replace(/\b\w/g, (char) => char.toUpperCase()) || 'Unknown'
}

function formatDate(value: string | null) {
  return value ? parse.formatDateTime(value) : '—'
}

onMounted(async () => {
  try {
    const response = await get<{ data?: any }>('admin/dashboard/summary')
    summary.value = response.data ?? null
  } finally {
    loading.value = false
  }
})
</script>

<style scoped></style>
