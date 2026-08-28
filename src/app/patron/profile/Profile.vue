<template>
  <main class="px-5 pb-24 pt-8 sm:px-8 sm:pb-12">
    <div class="mx-auto max-w-3xl">
      <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
          <div class="grid size-20 shrink-0 place-items-center rounded-2xl bg-emerald-100 text-2xl font-bold text-emerald-800">{{ auth.getInitials || 'MS' }}</div>
          <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">My account</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-950">{{ auth.getFullName || 'Mia Santos' }}</h1>
            <p class="mt-1 text-sm text-slate-500">Student member · ISU Main Campus</p>
          </div>
          <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700 sm:ml-auto">Active member</span>
        </div>
        <div class="mt-8 grid gap-4 border-t border-slate-100 pt-6 sm:grid-cols-2">
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Member ID</p>
            <p class="mt-1 font-semibold text-slate-800">ISU-2026-0148</p>
          </div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Email</p>
            <p class="mt-1 font-semibold text-slate-800">mia.santos@isu.edu.ph</p>
          </div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Home branch</p>
            <p class="mt-1 font-semibold text-slate-800">ISU Main Library</p>
          </div>
          <div>
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Member since</p>
            <p class="mt-1 font-semibold text-slate-800">August 2023</p>
          </div>
        </div>
      </section>
      <section class="mt-5 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="font-bold text-slate-950">Account preferences</h2>
        <div class="mt-4 divide-y divide-slate-100">
          <label v-for="preference in preferences" :key="preference.title" class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0"
            ><span
              ><span class="block text-sm font-semibold text-slate-800">{{ preference.title }}</span
              ><span class="mt-1 block text-xs text-slate-500">{{ preference.description }}</span></span
            ><input type="checkbox" :checked="preference.enabled" class="size-4 accent-emerald-700"
          /></label>
        </div>
      </section>
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
const preferences = [
  { title: 'Due date reminders', description: 'Receive an email before an item is due.', enabled: true },
  { title: 'New arrivals', description: 'Hear about new titles in your favorite subjects.', enabled: false },
  { title: 'Event updates', description: 'Get notified about library workshops and events.', enabled: true },
]
</script>

<style scoped></style>
