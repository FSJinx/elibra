<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="rounded-2xl bg-violet-950 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-violet-300">Administration</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ title }}</h1>
        <p class="mt-2 text-sm text-violet-100">Control access, policies, locations, and library identifiers.</p>
      </section>
      <section class="grid gap-4 sm:grid-cols-3">
        <article v-for="stat in stats" :key="stat.label" class="rounded-xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
          <p class="text-sm text-slate-500">{{ stat.label }}</p>
          <p class="mt-2 text-3xl font-bold text-slate-950">{{ stat.value }}</p>
        </article>
      </section>
      <section class="grid gap-3 md:grid-cols-2">
        <button v-for="item in items" :key="item.title" type="button" class="flex items-center gap-4 rounded-xl border border-slate-200 bg-white p-5 text-left shadow-sm transition hover:border-violet-300">
          <span class="grid size-11 place-items-center rounded-lg bg-violet-50 text-violet-700"><Icon :icon="item.icon" /></span
          ><span class="flex-1"
            ><strong class="block text-slate-800">{{ item.title }}</strong
            ><small class="mt-1 block text-slate-500">{{ item.detail }}</small></span
          ><Icon icon="arrow-right" class="text-slate-400" />
        </button>
      </section>
    </div>
  </main>
</template>
+
<script setup lang="ts">
const key = String(useRoute().name).split('.').pop()
const title = key === 'staff' ? 'Librarians / Staff' : key === 'roles' ? 'Roles & Permissions' : key === 'policies' ? 'Library Policies' : key === 'branches' ? 'Library Branches' : 'Barcode Generator'
const stats = [
  { label: 'Active records', value: key === 'staff' ? '11' : '24' },
  { label: 'Pending review', value: '3' },
  { label: 'Last audit', value: 'Aug 26' },
]
const items = [
  { icon: 'people', title: 'Staff access roster', detail: '11 active librarians across 3 branches' },
  { icon: 'shield-check', title: 'Borrowing policy', detail: 'Published August 12, 2026' },
  { icon: 'building', title: 'Main Library branch', detail: 'Open today until 8:00 PM' },
  { icon: 'upc-scan', title: 'Barcode batch 2026-08', detail: '125 identifiers ready to print' },
]
</script>
