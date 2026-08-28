<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="rounded-2xl bg-sky-950 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-sky-300">Patron module</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ page.title }}</h1>
        <p class="mt-2 text-sm text-sky-100">{{ page.description }}</p>
      </section>
      <section class="grid gap-4 sm:grid-cols-3">
        <article v-for="stat in page.stats" :key="stat.label" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <p class="text-sm text-slate-500">{{ stat.label }}</p>
          <p class="mt-2 text-3xl font-bold text-slate-950">{{ stat.value }}</p>
        </article>
      </section>
      <section class="rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 p-5">
          <h2 class="font-semibold text-slate-950">{{ page.section }}</h2>
          <button type="button" class="rounded-lg bg-sky-700 px-4 py-2.5 text-sm font-semibold text-white">{{ page.action }}</button>
        </div>
        <div class="grid gap-3 p-5 sm:grid-cols-2 lg:grid-cols-3">
          <article v-for="item in page.items" :key="item[0]" class="rounded-xl border border-slate-100 bg-slate-50 p-4">
            <p class="font-semibold text-slate-800">{{ item[0] }}</p>
            <p class="mt-1 text-sm text-slate-500">{{ item[1] }}</p>
            <span class="mt-3 inline-block rounded-full bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700">{{ item[2] }}</span>
          </article>
        </div>
      </section>
    </div>
  </main>
</template>
<script setup lang="ts">
const route = useRoute()
const key = String(route.name).split('.').pop()
const page = {
  title: key === 'patron-groups' ? 'Patron Groups' : key === 'patron-activity' ? 'Patron Activity History' : key === 'borrowing-history' ? 'Borrowing History' : 'Patrons',
  description: key === 'patron-groups' ? 'Configure membership groups and their borrowing rules.' : key === 'patron-activity' ? 'Audit account events across library services.' : key === 'borrowing-history' ? 'Review lending patterns and returned materials.' : 'Manage patron accounts, status, and contact information.',
  section: key === 'patrons' ? 'Patron directory' : 'Latest records',
  action: key === 'patrons' ? 'Register patron' : 'Export records',
  stats:
    key === 'patrons'
      ? [
          { label: 'Active members', value: '1,250' },
          { label: 'New this month', value: '48' },
          { label: 'Accounts to review', value: '12' },
        ]
      : [
          { label: 'Records this month', value: '386' },
          { label: 'Active today', value: '74' },
          { label: 'Needs attention', value: '6' },
        ],
  items: [
    ['Mia Santos', key === 'patrons' ? 'ISU-2026-0148 · Student' : '3 loans · Main Library', 'Active'],
    ['Jon Bell', key === 'patrons' ? 'ISU-2025-0931 · Faculty' : '1 renewal · Science Wing', 'Active'],
    ['Leah Tan', key === 'patrons' ? 'ISU-2024-0720 · Graduate' : 'Returned 2 items · Aug 25', 'Review'],
  ],
}
</script>
