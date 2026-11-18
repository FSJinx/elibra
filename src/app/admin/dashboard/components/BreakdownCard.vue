<template>
  <article class="rounded-xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-4">
      <h2 class="font-semibold text-slate-900">{{ title }}</h2>
      <p class="mt-1 text-sm text-slate-500">{{ subtitle }}</p>
    </div>
    <div class="divide-y divide-slate-100 px-5">
      <div v-for="row in rows" :key="row.label" class="flex items-center gap-3 py-3">
        <span class="w-32 truncate text-sm text-slate-700" :title="row.label">{{ row.label }}</span>
        <div class="h-2 flex-1 overflow-hidden rounded-full bg-slate-100">
          <div class="h-full rounded-full" :class="barClass" :style="{ width: `${percentage(row.total)}%` }"></div>
        </div>
        <span class="w-12 text-right text-sm font-semibold">{{ Number(row.total).toLocaleString() }}</span>
      </div>
      <p v-if="!rows.length" class="py-12 text-center text-sm text-slate-500">No records found.</p>
    </div>
  </article>
</template>

<script setup lang="ts">
const props = defineProps<{
  title: string
  subtitle: string
  rows: Array<{ label: string; total: number }>
  status?: boolean
}>()

const barClass = computed(() => (props.status ? 'bg-amber-500' : 'bg-primary'))
const total = computed(() => props.rows.reduce((sum, row) => sum + Number(row.total), 0))

function percentage(value: number) {
  return total.value > 0 ? Math.max(2, (Number(value) / total.value) * 100) : 0
}
</script>
