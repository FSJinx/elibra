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
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4" aria-label="Collection summary">
        <article v-for="metric in metrics" :key="metric.label" class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
          <div class="flex items-start justify-between">
            <p class="text-sm font-medium text-slate-500">{{ metric.label }}</p>
            <span class="flex size-9 items-center justify-center rounded-md" :class="metric.iconClass">
              <Icon :icon="metric.icon" />
            </span>
          </div>
          <p class="mt-5 text-3xl font-semibold text-slate-900">{{ metric.value }}</p>
          <p class="mt-1 text-xs text-slate-400">{{ metric.note }}</p>
        </article>
      </section>

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

    <div class="h-screen bg-primary"></div>
  </main>
</template>

<script setup lang="ts">
const clock = useClock()
const auth = authStore()

const metrics = [
  { label: 'Total collection', value: '-', note: 'Awaiting collection data', icon: 'library', iconClass: 'bg-emerald-50 text-emerald-600' },
  { label: 'On loan', value: '-', note: 'Circulation data pending', icon: 'book', iconClass: 'bg-sky-50 text-sky-600' },
  { label: 'Overdue items', value: '-', note: 'No live data yet', icon: 'clock', iconClass: 'bg-amber-50 text-amber-600' },
  { label: 'Active patrons', value: '-', note: 'Patron data pending', icon: 'people', iconClass: 'bg-violet-50 text-violet-600' },
]

const activities = [
  { title: 'Collection activity will appear here', description: 'New records, updates, and circulation events will be listed in this feed.', time: 'Soon', icon: 'activity', iconClass: 'bg-emerald-50 text-emerald-600' },
  { title: 'Quick actions are being prepared', description: 'Create records, manage loans, and review requests from one place.', time: 'Soon', icon: 'zap', iconClass: 'bg-sky-50 text-sky-600' },
  { title: 'Reports will be available here', description: 'Track collection health and daily service activity at a glance.', time: 'Soon', icon: 'chart-bar', iconClass: 'bg-violet-50 text-violet-600' },
]
</script>

<style scoped></style>
