<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="flex flex-col gap-4 rounded-2xl bg-amber-950 p-6 text-white sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-amber-300">Cataloging & collections</p>
          <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ page.title }}</h1>
          <p class="mt-2 text-sm text-amber-100">{{ page.description }}</p>
        </div>
        <button type="button" class="rounded-lg bg-amber-300 px-4 py-2.5 text-sm font-bold text-amber-950">{{ page.action }}</button>
      </section>
      <section class="grid gap-4 sm:grid-cols-3">
        <article v-for="stat in page.stats" :key="stat.label" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <p class="text-sm text-slate-500">{{ stat.label }}</p>
          <p class="mt-2 text-3xl font-bold text-slate-950">{{ stat.value }}</p>
        </article>
      </section>
      <section class="grid gap-6 lg:grid-cols-[1fr_280px]">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <h2 class="font-semibold text-slate-950">{{ page.listTitle }}</h2>
          <div class="mt-4 space-y-3">
            <article v-for="item in page.items" :key="item.name" class="flex items-center gap-4 rounded-xl border border-slate-100 p-4">
              <span class="grid size-10 shrink-0 place-items-center rounded-lg bg-amber-50 text-amber-700"><Icon :icon="item.icon" /></span>
              <div class="min-w-0 flex-1">
                <p class="font-semibold text-slate-800">{{ item.name }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ item.detail }}</p>
              </div>
              <span class="text-xs font-semibold text-slate-500">{{ item.status }}</span>
            </article>
          </div>
        </div>
        <aside class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
          <h2 class="font-semibold text-slate-950">Quick tools</h2>
          <div class="mt-4 space-y-2">
            <button v-for="tool in page.tools" :key="tool" type="button" class="flex w-full items-center justify-between rounded-lg bg-slate-50 px-3 py-3 text-left text-sm font-semibold text-slate-700">{{ tool }} <Icon icon="arrow-right" /></button>
          </div>
        </aside>
      </section>
    </div>
  </main>
  +
</template>
+
<script setup lang="ts">
+const route = useRoute(); const key = String(route.name).split('.').pop(); const page = key === 'inventory' ? { title: 'Inventory', description: 'Locate items, verify conditions, and reconcile shelf counts.', action: 'Start stocktake', listTitle: 'Stocktake activity', stats: [{ label: 'Items counted', value: '4,982' }, { label: 'Missing items', value: '18' }, { label: 'Shelves remaining', value: '24' }], tools: ['Scan shelf barcode', 'View missing items', 'Print shelf list'], items: [{ icon: 'boxes', name: 'Main Library · A-01 to A-12', detail: '1,248 items checked today', status: 'Complete' }, { icon: 'search', name: 'Science Wing · B-01 to B-08', detail: 'Next scheduled area', status: 'Pending' }, { icon: 'alert-triangle', name: 'Items requiring review', detail: '18 records need attention', status: 'Review' }] } : key === 'classification' ? { title: 'Classification', description: 'Assign call numbers and maintain the library classification map.', action: 'New classification', listTitle: 'Classification changes', stats: [{ label: 'Active schemes', value: '3' }, { label: 'Pending records', value: '21' }, { label: 'Mapped subjects', value: '486' }], tools: ['Browse Dewey map', 'Review suggestions', 'Import mapping'], items: [{ icon: 'tags', name: 'QA76.73 · Programming', detail: '42 linked catalog records', status: 'Mapped' }, { icon: 'tags', name: 'LB1028 · Education', detail: '8 suggestions waiting', status: 'Review' }, { icon: 'book', name: 'New title batch · 2026-08', detail: '125 items awaiting call numbers', status: 'Pending' }] } : { title: 'Authority Control', description: 'Keep author, subject, and organization headings consistent.', action: 'New authority record', listTitle: 'Authority review queue', stats: [{ label: 'Verified headings', value: '2,418' }, { label: 'Duplicates found', value: '14' }, { label: 'Linked records', value: '5,902' }], tools: ['Find duplicates', 'Browse authors', 'Export headings'], items: [{ icon: 'person', name: 'Murakami, Haruki', detail: '42 linked records · Personal name', status: 'Verified' }, { icon: 'people', name: 'Isabela State University', detail: '9 linked records · Organization', status: 'Verified' }, { icon: 'tag', name: 'Education policy', detail: '18 linked records · Subject', status: 'Review' }] }
+
</script>
