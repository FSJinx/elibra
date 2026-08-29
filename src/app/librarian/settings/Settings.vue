<template>
  <main class="min-h-full bg-slate-50 p-5 sm:p-7">
    <div class="mx-auto max-w-3xl space-y-6">
      <section class="rounded-2xl bg-teal-950 p-6 text-white">
        <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-300">Settings</p>
        <h1 class="mt-2 text-2xl font-bold sm:text-3xl">{{ title }}</h1>
        <p class="mt-2 text-sm text-teal-100">Tune the library workspace and account behavior.</p>
      </section>
      <section class="divide-y divide-slate-100 rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
        <label v-for="setting in settings" :key="setting.name" class="flex items-center justify-between gap-5 py-5 first:pt-0 last:pb-0"
          ><span
            ><strong class="block text-slate-800">{{ setting.name }}</strong
            ><small class="mt-1 block text-slate-500">{{ setting.detail }}</small></span
          ><input v-if="setting.toggle" type="checkbox" :checked="setting.enabled" class="size-5 accent-teal-700" /><span v-else class="rounded-lg bg-teal-50 px-3 py-2 text-xs font-bold text-teal-700">{{ setting.value }}</span></label
        >
      </section>
      <button type="button" class="rounded-lg bg-teal-700 px-4 py-3 text-sm font-semibold text-white">Save changes</button>
    </div>
  </main>
</template>
+
<script setup lang="ts">
const key = String(useRoute().name).split('.').pop()
const title = key === 'system' ? 'System Settings' : key === 'preferences' ? 'Preferences' : key === 'profile' ? 'Profile' : 'Account'
const settings = [
  { name: 'Default branch', detail: 'Used when opening circulation queues.', value: 'Main Library', toggle: false },
  { name: 'Daily digest', detail: 'Send a summary of desk activity each morning.', enabled: true, toggle: true },
  { name: 'Compact tables', detail: 'Fit more records into operational lists.', enabled: false, toggle: true },
  { name: 'Timezone', detail: 'Dates and reports use this local timezone.', value: 'Asia/Manila', toggle: false },
]
</script>
