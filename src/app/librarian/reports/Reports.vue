<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="rounded-2xl bg-slate-900 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-300">Reports & analytics</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ title }} report</h1>
        <p class="mt-2 text-sm text-slate-300">A live-looking sample of this report’s key measures.</p>
      </section>
      <section class="grid gap-4 sm:grid-cols-3">
        <article v-for="metric in metrics" :key="metric.label" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
          <div class="flex items-center justify-between">
            <p class="text-sm text-slate-500">{{ metric.label }}</p>
            <span class="text-sm font-bold text-cyan-700">{{ metric.change }}</span>
          </div>
          <p class="mt-3 text-3xl font-bold text-slate-950">{{ metric.value }}</p>
          <div class="mt-4 h-2 rounded-full bg-slate-100"><div class="h-full rounded-full bg-cyan-600" :style="{ width: metric.width }"></div></div>
        </article>
      </section>
      <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="font-semibold text-slate-950">Weekly movement</h2>
            <p class="mt-1 text-sm text-slate-500">Sample activity for the last seven days.</p>
          </div>
          <button type="button" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold">Download CSV</button>
        </div>
        <div class="mt-6 flex h-44 items-end gap-3 sm:gap-6">
          <div v-for="bar in bars" :key="bar.day" class="flex flex-1 flex-col items-center gap-2">
            <div class="w-full rounded-t-md bg-cyan-600" :style="{ height: bar.height }"></div>
            <span class="text-xs text-slate-400">{{ bar.day }}</span>
          </div>
        </div>
      </section>
    </div>
  </main>
</template>
+
<script setup lang="ts">
const key = String(useRoute().name).split('.').pop()
const title = key?.charAt(0).toUpperCase() + (key as string).slice(1)
const metrics = [
  { label: title === 'Overdue' ? 'Overdue items' : 'Total activity', value: title === 'Overdue' ? '42' : '1,284', change: '+16%', width: '78%' },
  { label: 'Compared with last month', value: '1,106', change: '+8%', width: '64%' },
  { label: 'Report confidence', value: '98.4%', change: 'Stable', width: '92%' },
]
const bars = [
  { day: 'Mon', height: '48%' },
  { day: 'Tue', height: '68%' },
  { day: 'Wed', height: '55%' },
  { day: 'Thu', height: '82%' },
  { day: 'Fri', height: '72%' },
  { day: 'Sat', height: '42%' },
  { day: 'Sun', height: '28%' },
]
</script>
