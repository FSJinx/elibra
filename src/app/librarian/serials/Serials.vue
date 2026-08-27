<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="rounded-2xl bg-rose-950 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-rose-300">Serials</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ page.title }}</h1>
        <p class="mt-2 text-sm text-rose-100">{{ page.description }}</p>
      </section>
      <section class="grid gap-4 sm:grid-cols-3">
        <article v-for="stat in stats" :key="stat.label" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <p class="text-sm text-slate-500">{{ stat.label }}</p>
          <p class="mt-2 text-3xl font-bold text-slate-950">{{ stat.value }}</p>
        </article>
      </section>
      <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 p-5">
          <h2 class="font-semibold text-slate-950">{{ page.section }}</h2>
          <p class="mt-1 text-sm text-slate-500">Keep recurring publications accounted for.</p>
        </div>
        <div class="divide-y divide-slate-100">
          <article v-for="item in items" :key="item.title" class="flex flex-col gap-3 p-5 sm:flex-row sm:items-center">
            <span class="grid size-11 place-items-center rounded-lg bg-rose-50 text-rose-700"><Icon icon="journals" /></span>
            <div class="flex-1">
              <h3 class="font-semibold text-slate-800">{{ item.title }}</h3>
              <p class="mt-1 text-sm text-slate-500">{{ item.detail }}</p>
            </div>
            <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700">{{ item.status }}</span
            ><button type="button" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-semibold">Open record</button>
          </article>
        </div>
      </section>
    </div>
  </main>
</template>
+
<script setup lang="ts">
const key = String(useRoute().name).split('.').pop()
const page = key === 'subscriptions' ? { title: 'Subscriptions', description: 'Monitor journals and digital resource access.', section: 'Active subscriptions' } : key === 'issue-tracking' ? { title: 'Issue Tracking', description: 'Receive, check, and route every expected serial issue.', section: 'Expected issues' } : { title: 'Serials Cataloging', description: 'Maintain serial publication records and holdings.', section: 'Serial catalog' }
const stats = [
  { label: 'Active titles', value: '42' },
  { label: 'Expected this week', value: '8' },
  { label: 'Needs cataloging', value: '5' },
]
const items = [
  { title: 'Journal of Education · Vol. 12 No. 3', detail: 'Expected August 29 · Periodicals A', status: 'Expected' },
  { title: 'Philippine Studies · Vol. 12 No. 2', detail: 'Received August 25 · Periodicals A', status: 'Cataloged' },
  { title: 'Science Weekly · Issue 34', detail: 'Digital delivery · October plan', status: 'Active' },
]
</script>
