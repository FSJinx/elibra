<template>
  <header v-if="$route.meta.title" class="shrink-0 p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
      <div class="min-w-0">
        <p class="mb-1.5 text-xs font-semibold uppercase tracking-widest text-primary">{{ auth.user?.role }} Desk</p>
        <h1 class="text-2xl font-bold capitalize tracking-tight text-foreground">Good day, {{ auth.user?.first_name }}!</h1>
        <p class="mt-1 max-w-2xl text-sm leading-relaxed text-foreground-secondary">
          {{ $route.meta.description ?? "Here is your library's performance overview." }}
        </p>
      </div>
    </div>
  </header>

  <main class="min-h-full bg-slate-50 p-5">
    <div class="mx-auto space-y-6">
      <section aria-label="Library metrics">
        <div class="mb-3 flex items-center justify-between">
          <Title :level="4" class="uppercase tracking-wider text-primary">Library at a glance</Title>
          <span class="text-sm text-slate-500">{{ snapshotLabel }}</span>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
          <StatCard label="Catalog items" :value="metrics.catalog_items" icon="book" variant="primary" :loading="dashboard.loading" />
          <StatCard label="Physical copies" :value="metrics.copies" icon="journals" variant="success" :loading="dashboard.loading" />
          <StatCard label="Available copies" :value="metrics.available_copies" icon="check-circle" variant="success" :loading="dashboard.loading" />
          <StatCard label="Active loans" :value="metrics.active_loans" icon="arrow-left-right" variant="warning" :loading="dashboard.loading" />
          <StatCard label="Overdue loans" :value="metrics.overdue_loans" icon="clock-history" variant="danger" :loading="dashboard.loading" />
          <StatCard label="Borrowed today" :value="metrics.borrowed_today" icon="box-arrow-in-right" variant="info" :loading="dashboard.loading" />
          <StatCard label="Returned today" :value="metrics.returned_today" icon="box-arrow-left" variant="success" :loading="dashboard.loading" />
          <StatCard label="Library patrons" :value="metrics.patrons" icon="people" variant="restore" :loading="dashboard.loading" />
          <StatCard label="Total circulation records" :value="metrics.total_circulations" icon="arrow-repeat" variant="primary" :loading="dashboard.loading" />
          <StatCard label="Borrowed copies" :value="metrics.borrowed_copies" icon="box-arrow-right" variant="warning" :loading="dashboard.loading" />
          <StatCard label="Librarians" :value="metrics.librarians" icon="person-workspace" variant="info" :loading="dashboard.loading" />
          <StatCard label="Libraries" :value="metrics.libraries" icon="building" variant="success" :loading="dashboard.loading" />
        </div>
      </section>

      <section class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]" aria-label="Activity and service overview">
        <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
              <h2 class="font-semibold text-slate-900">Circulation activity</h2>
              <p class="mt-1 text-sm text-slate-500">Daily borrowed and returned records over the last 14 days</p>
            </div>
            <div class="flex gap-3 text-xs text-slate-500">
              <span class="flex items-center gap-1"><i class="size-2 rounded-full bg-primary"></i>Borrowed</span>
              <span class="flex items-center gap-1"><i class="size-2 rounded-full bg-emerald-500"></i>Returned</span>
            </div>
          </div>
          <div class="h-72 p-5">
            <div v-if="dashboard.loading" class="h-full animate-pulse rounded-lg bg-slate-100"></div>
            <div v-else class="flex h-full items-end gap-2 border-b border-slate-200">
              <div v-for="day in dailyActivity" :key="day.date" class="relative flex h-full min-w-0 flex-1 items-end justify-center gap-1" :title="`${day.label}: ${day.borrowed} borrowed, ${day.returned} returned`">
                <div class="w-[42%] rounded-t bg-primary/70 transition-all" :style="{ height: `${barHeight(day.borrowed)}%` }"></div>
                <div class="w-[42%] rounded-t bg-emerald-500/70 transition-all" :style="{ height: `${barHeight(day.returned)}%` }"></div>
                <span class="absolute mt-2 translate-y-full text-[10px] text-slate-400">{{ day.label.replace(/^[A-Za-z]+ /, '') }}</span>
              </div>
            </div>
          </div>
        </article>

        <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Copy status</h2>
            <p class="mt-1 text-sm text-slate-500">Distribution of all copies in scope</p>
          </div>
          <div v-if="dashboard.loading" class="p-5"><div class="h-52 animate-pulse rounded-lg bg-slate-100"></div></div>
          <div v-else class="space-y-4 p-5">
            <div v-for="row in accessionStatuses" :key="row.status" class="space-y-1.5">
              <div class="flex justify-between text-sm">
                <span class="capitalize text-slate-700">{{ statusLabel(row.status) }}</span>
                <span class="font-semibold text-slate-900">{{ Number(row.total).toLocaleString() }}</span>
              </div>
              <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-primary/75" :style="{ width: `${percentage(row.total, accessionTotal)}%` }"></div>
              </div>
            </div>
            <p v-if="!accessionStatuses.length" class="py-12 text-center text-sm text-slate-500">No accession records found.</p>
          </div>
        </article>
      </section>

      <section class="grid gap-6 lg:grid-cols-2" aria-label="Collection breakdowns">
        <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Collection by item type</h2>
            <p class="mt-1 text-sm text-slate-500">Largest catalog categories in the current scope</p>
          </div>
          <div class="divide-y divide-slate-100 px-5">
            <div v-for="row in itemTypes" :key="row.name" class="flex items-center gap-3 py-3">
              <span class="w-36 truncate text-sm text-slate-700" :title="row.name">{{ row.name }}</span>
              <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-sky-500" :style="{ width: `${percentage(row.total, itemTypeTotal)}%` }"></div>
              </div>
              <span class="w-12 text-right text-sm font-semibold">{{ Number(row.total).toLocaleString() }}</span>
            </div>
            <p v-if="!itemTypes.length" class="py-12 text-center text-sm text-slate-500">No item types found.</p>
          </div>
        </article>

        <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
          <div class="border-b border-slate-200 px-5 py-4">
            <h2 class="font-semibold text-slate-900">Patrons by type</h2>
            <p class="mt-1 text-sm text-slate-500">Registered patron distribution</p>
          </div>
          <div class="divide-y divide-slate-100 px-5">
            <div v-for="row in patronTypes" :key="row.name" class="flex items-center gap-3 py-3">
              <span class="w-36 truncate text-sm text-slate-700" :title="row.name">{{ row.name }}</span>
              <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-violet-500" :style="{ width: `${percentage(row.total, patronTypeTotal)}%` }"></div>
              </div>
              <span class="w-12 text-right text-sm font-semibold">{{ Number(row.total).toLocaleString() }}</span>
            </div>
            <p v-if="!patronTypes.length" class="py-12 text-center text-sm text-slate-500">No patron types found.</p>
          </div>
        </article>
      </section>

      <section class="grid gap-6 xl:grid-cols-[minmax(0,1.4fr)_minmax(320px,.6fr)]">
        <Table title="Recent circulations" subtitle="Latest borrowing and return activity" :data="{ data: recentCirculations, total: recentCirculations.length } as Pagination">
          <Thead>
            <tr>
              <th class="text-left">Borrower</th>
              <th class="text-left">Title</th>
              <th>Status</th>
              <th>Borrowed</th>
              <th>Due</th>
            </tr>
          </Thead>
          <Tbody :data="recentCirculations" :loading="dashboard.loading" cols="5">
            <Tr v-for="loan in recentCirculations" :key="loan.id">
              <Td class="text-left" align="left">
                <p class="font-medium">{{ loan.borrower }}</p>
                <p class="text-xs text-slate-500">{{ loan.patron_type ?? 'Patron' }}</p>
              </Td>
              <Td class="text-left" align="left">
                <p class="max-w-72 truncate font-medium" :title="loan.title">{{ loan.title ?? 'Unknown title' }}</p>
                <p class="text-xs text-slate-500">{{ loan.call_number ?? 'No call number' }}</p>
              </Td>
              <Td
                ><Status :variant="loan.status">{{ statusLabel(loan.status) }}</Status></Td
              >
              <Td :data="formatDate(loan.borrowed_at)" />
              <Td :data="formatDate(loan.due_at)" />
            </Tr>
          </Tbody>
        </Table>

        <Table title="Recent catalog records" subtitle="Latest added items" :data="{ data: recentItems, total: recentItems.length } as Pagination">
          <Thead>
            <tr>
              <th class="text-left">Title</th>
              <th>Added</th>
            </tr>
          </Thead>
          <Tbody :data="recentItems" :loading="dashboard.loading" cols="2">
            <Tr v-for="item in recentItems" :key="item.id">
              <Td class="text-left" align="left">
                <p class="max-w-64 truncate font-medium" :title="item.title">{{ item.title }}</p>
                <p class="text-xs text-slate-500">{{ item.item_type ?? 'Unknown type' }} · {{ item.call_number ?? 'No call number' }}</p>
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
import type { Pagination } from '@/services/pagination'

const clock = useClock()
const auth = authStore()
const dashboard = librarianDashboardStore()
const parse = useParser()

const metrics = computed(() => dashboard.summary?.metrics ?? {})
const dailyActivity = computed(() => dashboard.summary?.daily_activity ?? [])
const accessionStatuses = computed(() => dashboard.summary?.accession_statuses ?? [])
const itemTypes = computed(() => dashboard.summary?.item_types ?? [])
const patronTypes = computed(() => dashboard.summary?.patron_types ?? [])
const recentCirculations = computed(() => dashboard.summary?.recent_circulations ?? [])
const recentItems = computed(() => dashboard.summary?.recent_items ?? [])

const snapshotLabel = computed(() => (dashboard.loading ? 'Refreshing live records...' : `Live snapshot · ${clock.today.value}`))
const accessionTotal = computed(() => accessionStatuses.value.reduce((sum: number, row: any) => sum + Number(row.total), 0))
const itemTypeTotal = computed(() => itemTypes.value.reduce((sum: number, row: any) => sum + Number(row.total), 0))
const patronTypeTotal = computed(() => patronTypes.value.reduce((sum: number, row: any) => sum + Number(row.total), 0))
const activityMax = computed(() => Math.max(1, ...dailyActivity.value.flatMap((day: any) => [Number(day.borrowed), Number(day.returned)])))

function barHeight(value: number) {
  return Math.max(value ? 8 : 2, (Number(value) / activityMax.value) * 100)
}

function percentage(value: number, total: number) {
  return total > 0 ? Math.max(2, (Number(value) / total) * 100) : 0
}

function statusLabel(status: string) {
  return (
    String(status ?? '')
      .replaceAll('_', ' ')
      .replace(/\b\w/g, (char) => char.toUpperCase()) || 'Unknown'
  )
}

function formatDate(value: string | null) {
  return value ? parse.formatDateTime(value) : '—'
}

onMounted(() => dashboard.fetch())
</script>

<style scoped></style>
