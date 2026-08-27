<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="rounded-2xl bg-indigo-950 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-indigo-300">Acquisitions</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ page.title }}</h1>
        <p class="mt-2 text-sm text-indigo-100">{{ page.description }}</p>
      </section>
      <section class="grid gap-4 sm:grid-cols-3">
        <article v-for="stat in page.stats" :key="stat.label" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <p class="text-sm text-slate-500">{{ stat.label }}</p>
          <p class="mt-2 text-2xl font-bold text-slate-950">{{ stat.value }}</p>
        </article>
      </section>
      <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
          <h2 class="font-semibold text-slate-950">{{ page.section }}</h2>
          <button type="button" class="rounded-lg bg-indigo-700 px-3 py-2 text-sm font-semibold text-white">{{ page.action }}</button>
        </div>
        <div class="mt-4 grid gap-3 md:grid-cols-2">
          <article v-for="item in page.items" :key="item.name" class="rounded-xl bg-indigo-50/60 p-4">
            <div class="flex justify-between gap-3">
              <h3 class="font-semibold text-slate-800">{{ item.name }}</h3>
              <span class="text-xs font-bold text-indigo-700">{{ item.status }}</span>
            </div>
            <p class="mt-2 text-sm text-slate-500">{{ item.detail }}</p>
            <div class="mt-3 h-1.5 rounded-full bg-indigo-100"><div class="h-full rounded-full bg-indigo-600" :style="{ width: item.progress + '%' }"></div></div>
          </article>
        </div>
      </section>
    </div>
  </main>
</template>
+
<script setup lang="ts">
const key = String(useRoute().name).split('.').pop()
const page =
  key === 'vendors'
    ? {
        title: 'Vendors',
        description: 'Manage suppliers, terms, and renewal contacts.',
        section: 'Supplier directory',
        action: 'Add vendor',
        stats: [
          { label: 'Active vendors', value: '18' },
          { label: 'Contracts renewing', value: '3' },
          { label: 'Preferred suppliers', value: '7' },
        ],
        items: [
          { name: 'EBSCO Information Services', detail: 'Digital resources · Net 30 terms', status: 'Active', progress: 82 },
          { name: 'University Press', detail: 'Books · Preferred supplier', status: 'Active', progress: 64 },
          { name: 'Archive Systems PH', detail: 'Equipment · Contract review', status: 'Review', progress: 38 },
        ],
      }
    : {
        title: key === 'requests' ? 'Requests' : key === 'purchase-orders' ? 'Purchase Orders' : key === 'budget-funds' ? 'Budget & Funds' : 'Donations & Gifts',
        description: 'Move library resources from request to availability with clear ownership.',
        section: key === 'budget-funds' ? 'Fund utilization' : 'Acquisition pipeline',
        action: key === 'requests' ? 'New request' : 'Export',
        stats: [
          { label: 'Open requests', value: '24' },
          { label: 'Committed funds', value: 'PHP 486K' },
          { label: 'Received this month', value: '86' },
        ],
        items: [
          { name: 'Engineering reference titles', detail: 'Requested by Engineering Department · PHP 12,800', status: 'Pending', progress: 38 },
          { name: 'PO-3021 · National Book Store', detail: '48 titles · Expected September 04', status: 'Approved', progress: 72 },
          { name: 'ISU Alumni donation', detail: '35 items · Received August 28', status: 'Complete', progress: 100 },
        ],
      }
</script>
