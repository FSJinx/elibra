<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="rounded-2xl bg-slate-950 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">Librarian module</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ title }}</h1>
        <p class="mt-2 text-sm text-slate-300">{{ subtitle }}</p>
      </section>
      <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center justify-between border-b border-slate-200 p-5">
          <div>
            <h2 class="font-semibold text-slate-950">{{ title }} records</h2>
            <p class="mt-1 text-sm text-slate-500">{{ rows.length }} sample records</p>
          </div>
          <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white"><Icon icon="plus-lg" /> Add record</button>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-160 w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
              <tr>
                <th v-for="column in columns" :key="column" class="px-5 py-3 font-semibold">{{ column }}</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="(row, index) in rows" :key="index" class="hover:bg-emerald-50/40">
                <td v-for="(cell, cellIndex) in row" :key="cellIndex" class="px-5 py-4" :class="cellIndex === 0 ? 'font-semibold text-slate-800' : 'text-slate-500'">
                  <span v-if="cellIndex === row.length - 1" class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">{{ cell }}</span
                  ><template v-else>{{ cell }}</template>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>
    </div>
  </main>
</template>
<script setup lang="ts">
defineProps<{ title: string; subtitle: string; columns: string[]; rows: string[][] }>()
</script>
