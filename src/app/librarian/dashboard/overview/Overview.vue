<template>
  <header v-if="$route.meta.title" class="shrink-0 p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
      <!-- Page Information -->
      <div class="min-w-0">
        <p class="mb-1.5 text-xs font-semibold uppercase tracking-widest text-primary">{{ auth.user?.role }} Desk</p>

        <h1 class="text-2xl font-bold capitalize tracking-tight text-foreground">Good day, {{ auth.user?.first_name }}!</h1>

        <p class="mt-1 max-w-2xl text-sm leading-relaxed text-foreground-secondary">
          {{ $route.meta.description ?? 'This is the default description for pages.' }}
        </p>
      </div>

      <!-- Date -->
      <div class="flex shrink-0 items-center gap-2 text-foreground-secondary">
        <Icon icon="calendar" />
        <time>
          {{ clock.today }}
        </time>
      </div>
    </div>
  </header>

  <main class="min-h-full bg-slate-50 p-5">
    <div class="mx-auto space-y-6">
      <div class="flex flex-col gap-5">
        <Title :level="4" class="text-widest uppercase text-primary">Collection Metrics</Title>
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Collection summary">
          <StatCard v-for="metric in collectionMetrics" :label="metric.label" :value="Number(metric.value).toLocaleString()" :icon="metric.icon" :variant="metric.iconClass as Variants" :is-loading="metric.isLoading"></StatCard>
        </section>
      </div>
      <div class="flex flex-col gap-5">
        <Title :level="4" class="text-widest uppercase text-primary">User Metrics</Title>
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Collection summary">
          <StatCard v-for="metric in userMetrics" :label="metric.label" :value="Number(metric.value).toLocaleString()" :icon="metric.icon" :variant="metric.iconClass as Variants" :is-loading="metric.isLoading"></StatCard>
        </section>
      </div>

      <section class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_340px]">
        <article class="rounded-lg border border-slate-200 bg-white shadow-sm">
          <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
            <div>
              <h2 class="font-semibold text-slate-900">Recent activity</h2>
              <p class="mt-1 text-sm text-slate-500">A snapshot of the latest collection changes.</p>
            </div>
            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">Placeholder</span>
          </div>

          <div class="space-y-5 p-5">
            <div v-for="activity in activities" :key="activity.title" class="flex items-start gap-3">
              <span class="mt-0.5 flex size-9 shrink-0 items-center justify-center rounded-md" :class="activity.iconClass">
                <Icon :icon="activity.icon" />
              </span>
              <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-slate-800">{{ activity.title }}</p>
                <p class="mt-1 text-xs text-slate-500">{{ activity.description }}</p>
              </div>
              <span class="shrink-0 text-xs text-slate-400">{{ activity.time }}</span>
            </div>
          </div>
        </article>

        <aside class="rounded-lg border border-slate-200 bg-slate-900 p-6 text-white shadow-sm">
          <span class="flex size-10 items-center justify-center rounded-md bg-emerald-400/15 text-emerald-300">
            <Icon icon="sparkles" />
          </span>
          <h2 class="mt-5 text-xl font-semibold">Dashboard in progress</h2>
          <p class="mt-2 text-sm leading-6 text-slate-300">Live circulation, overdue items, and collection insights are being prepared for this workspace.</p>
          <div class="mt-6 h-1.5 overflow-hidden rounded-full bg-white/10">
            <div class="h-full w-2/5 rounded-full bg-emerald-400"></div>
          </div>
          <p class="mt-2 text-xs text-slate-400">Core modules coming together</p>
        </aside>
      </section>
    </div>
  </main>
</template>

<script setup lang="ts">
const clock = useClock()
const auth = authStore()
const dashboard = useLibrarianDashboardStore()

const collectionMetrics = computed(() => [
  { label: 'Total Collection', value: dashboard.totalCollections, icon: 'library', iconClass: 'bg-emerald-50 text-emerald-600', isLoading: dashboard.loadingTotalCollections },
  { label: 'Total Books', value: dashboard.totalBooks, icon: 'people', iconClass: 'bg-violet-50 text-violet-600', isLoading: dashboard.loadingTotalBooks },
  { label: 'Total Academics', value: dashboard.totalAcademics, icon: 'book', iconClass: 'bg-sky-50 text-sky-600', isLoading: dashboard.loadingTotalAcademics },
  { label: 'Total Serials', value: dashboard.totalSerials, icon: 'clock', iconClass: 'bg-amber-50 text-amber-600', isLoading: dashboard.loadingTotalSerials },
])

const userMetrics = computed(() => [
  { label: 'Total Campuses', value: dashboard.totalCampuses, icon: 'building', iconClass: 'bg-amber-50 text-amber-600', isLoading: dashboard.loadingTotalCampuses },
  { label: 'Total Branches', value: dashboard.totalBranches, icon: 'building', iconClass: 'bg-emerald-50 text-emerald-600', isLoading: dashboard.loadingTotalBranches },
  { label: 'Total Librarians', value: dashboard.totalLibrarians, icon: 'people', iconClass: 'bg-sky-50 text-sky-600', isLoading: dashboard.loadingTotalLibrarians },
  { label: 'Total Patrons', value: dashboard.totalPatrons, icon: 'people', iconClass: 'bg-violet-50 text-violet-600', isLoading: dashboard.loadingTotalPatrons },
])

const activities = [
  { title: 'Collection activity will appear here', description: 'New records, updates, and circulation events will be listed in this feed.', time: 'Soon', icon: 'activity', iconClass: 'bg-emerald-50 text-emerald-600' },
  { title: 'Quick actions are being prepared', description: 'Create records, manage loans, and review requests from one place.', time: 'Soon', icon: 'zap', iconClass: 'bg-sky-50 text-sky-600' },
  { title: 'Reports will be available here', description: 'Track collection health and daily service activity at a glance.', time: 'Soon', icon: 'chart-bar', iconClass: 'bg-violet-50 text-violet-600' },
]

onMounted(() => {
  dashboard.fetch()
})
</script>

<style scoped></style>
