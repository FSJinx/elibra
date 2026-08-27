<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="flex flex-col gap-5 rounded-2xl bg-emerald-900 p-6 text-white lg:flex-row lg:items-end lg:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">Circulation desk</p>
          <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ page.title }}</h1>
          <p class="mt-2 text-sm text-emerald-100">{{ page.description }}</p>
        </div>
        <div class="flex gap-2">
          <button v-for="action in page.actions" :key="action" type="button" class="rounded-lg bg-white px-3 py-2 text-sm font-semibold text-emerald-900">{{ action }}</button>
        </div>
      </section>
      <section class="grid gap-4 sm:grid-cols-3">
        <article v-for="metric in page.metrics" :key="metric.label" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <p class="text-sm text-slate-500">{{ metric.label }}</p>
          <p class="mt-2 text-3xl font-bold text-slate-950">{{ metric.value }}</p>
          <p class="mt-1 text-xs text-slate-400">{{ metric.note }}</p>
        </article>
      </section>
      <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 p-5">
          <div>
            <h2 class="font-semibold text-slate-950">{{ page.queue }}</h2>
            <p class="mt-1 text-sm text-slate-500">{{ rows.length }} items in this queue</p>
          </div>
          <input v-model="query" type="search" placeholder="Search queue" class="rounded-lg border border-slate-200 px-3 py-2 text-sm outline-none focus:border-emerald-500" />
        </div>
        <div class="divide-y divide-slate-100">
          <div v-for="row in filteredRows" :key="row.id" class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center">
            <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-emerald-50 font-bold text-emerald-700">{{ row.id }}</span>
            <div class="min-w-0 flex-1">
              <p class="font-semibold text-slate-800">{{ row.name }}</p>
              <p class="mt-1 text-sm text-slate-500">{{ row.detail }}</p>
            </div>
            <span class="text-sm font-semibold text-slate-600">{{ row.date }}</span
            ><button type="button" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold text-slate-700">{{ page.button }}</button>
          </div>
        </div>
      </section>
    </div>
  </main>
</template>
<script setup lang="ts">
const route = useRoute()
const query = ref('')
const key = computed(() => String(route.name).split('.').pop())
const content: Record<string, any> = {
  loans: {
    title: 'Check-Out / Loans',
    description: 'Scan a patron and issue materials from the desk.',
    queue: 'Ready for checkout',
    button: 'Check out',
    actions: ['Scan patron', 'Scan item'],
    metrics: [
      { label: 'Due today', value: 18, note: 'Items to monitor' },
      { label: 'Checked out today', value: 42, note: 'Across all branches' },
      { label: 'Desk queue', value: 7, note: 'Waiting patrons' },
    ],
  },
  returns: {
    title: 'Returns',
    description: 'Receive returned items and route them for shelving or review.',
    queue: 'Incoming returns',
    button: 'Receive',
    actions: ['Scan return', 'Print receipt'],
    metrics: [
      { label: 'Returned today', value: 31, note: 'Since opening' },
      { label: 'Needs inspection', value: 4, note: 'Condition review' },
      { label: 'To shelving', value: 22, note: 'Ready to route' },
    ],
  },
  renewals: {
    title: 'Renewals',
    description: 'Review and approve renewal requests before extending due dates.',
    queue: 'Renewal requests',
    button: 'Renew',
    actions: ['Approve all'],
    metrics: [
      { label: 'Requests', value: 14, note: 'Awaiting review' },
      { label: 'Approved today', value: 9, note: 'Due dates extended' },
      { label: 'Blocked', value: 2, note: 'Hold exists' },
    ],
  },
  holds: {
    title: 'Holds & Reservations',
    description: 'Prepare reserved titles and notify patrons when they are ready.',
    queue: 'Pickup queue',
    button: 'Mark ready',
    actions: ['Print slips'],
    metrics: [
      { label: 'Waiting pickup', value: 12, note: 'Patrons notified' },
      { label: 'Ready today', value: 6, note: 'Awaiting collection' },
      { label: 'Expired', value: 1, note: 'Needs follow-up' },
    ],
  },
  fines: {
    title: 'Fines & Penalties',
    description: 'Review outstanding balances and record desk payments.',
    queue: 'Outstanding balances',
    button: 'Record payment',
    actions: ['Export balances'],
    metrics: [
      { label: 'Open balances', value: 27, note: 'Active accounts' },
      { label: 'Collected today', value: 'PHP 2,450', note: 'Desk payments' },
      { label: 'Waived this month', value: 8, note: 'Approved requests' },
    ],
  },
  attendance: {
    title: 'Attendance',
    description: 'Log library visits and monitor traffic by branch.',
    queue: 'Today’s visits',
    button: 'View visit',
    actions: ['Log visit'],
    metrics: [
      { label: 'Visits today', value: 186, note: 'All branches' },
      { label: 'Peak hour', value: '2–3 PM', note: 'Main Library' },
      { label: 'Active now', value: 54, note: 'Estimated occupancy' },
    ],
  },
}
const page = computed(() => content[key.value] || content.loans)
const rows = [
  { id: '01', name: 'Mia Santos', detail: 'The Design of Everyday Things · Main Library', date: 'Today, 10:42 AM' },
  { id: '02', name: 'Jon Bell', detail: 'Introduction to Algorithms · Science Wing', date: 'Today, 10:28 AM' },
  { id: '03', name: 'Leah Tan', detail: 'Journal of Education, Vol. 12 · Periodicals', date: 'Today, 9:54 AM' },
]
const filteredRows = computed(() => rows.filter((row) => `${row.name} ${row.detail}`.toLowerCase().includes(query.value.toLowerCase())))
</script>
