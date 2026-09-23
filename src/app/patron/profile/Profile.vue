<template>
  <main class="px-5 pb-24 pt-8 sm:px-8 sm:pb-12">
    <div class="flex flex-col gap-5 mx-auto max-w-3xl">
      <Card>
        <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
          <div class="grid size-20 shrink-0 place-items-center rounded-2xl bg-emerald-100 text-2xl font-bold text-emerald-800">{{ auth.getInitials || 'MS' }}</div>
          <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">My account</p>
            <h1 class="mt-2 text-2xl font-bold text-slate-950">{{ auth.getFullName || 'Isabela State University' }}</h1>
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
      </Card>
      <Card>
        <h2 class="font-bold text-slate-950">Account preferences</h2>
        <div class="mt-4 divide-y divide-slate-100">
          <label v-for="preference in preferences" :key="preference.title" class="flex items-center justify-between gap-4 py-4 first:pt-0 last:pb-0"
            ><span
              ><span class="block text-sm font-semibold text-slate-800">{{ preference.title }}</span
              ><span class="mt-1 block text-xs text-slate-500">{{ preference.description }}</span></span
            ><input type="checkbox" :checked="preference.enabled" class="size-4 accent-emerald-700"
          /></label>
        </div>
<<<<<<< HEAD
      </section>
<<<<<<< HEAD
      <section class="mt-5 flex flex-col gap-4 rounded-2xl border border-rose-100 bg-rose-50/50 p-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="font-bold text-slate-950">Sign out</h2>
          <p class="mt-1 text-sm text-slate-500">Sign out securely from your library account on this device.</p>
        </div>
        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-3 text-sm font-bold text-white transition hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2" @click="account.logout">
          <Icon icon="box-arrow-right" />
          Log out
        </button>
=======
      <section class="flex mt-5 rounded-2xl b1order border-slate-200 bg-white p-6 shadow-sm">
        <Button class="w-full" variant="danger" @click="user.logout()">Logout</Button>
>>>>>>> dc589cece2127835548c01de82f4bd238c045bd6
      </section>
=======
      </Card>
      <Card>
        <div>
          <Title :level="4">Sign Out</Title>
          <p class="text-sm text-muted-foreground">Securely sign out this account to this device</p>
        </div>
        <Button variant="danger" @click="user.logout()">Logout</Button>
      </Card>
>>>>>>> e32688c1189fa09ce5f5fa44714f35f539d448dd
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()
<<<<<<< HEAD
const account = useAuth()
=======
const user = useAuth()
>>>>>>> dc589cece2127835548c01de82f4bd238c045bd6
const preferences = [
  { title: 'Due date reminders', description: 'Receive an email before an item is due.', enabled: true },
  { title: 'New arrivals', description: 'Hear about new titles in your favorite subjects.', enabled: false },
  { title: 'Event updates', description: 'Get notified about library workshops and events.', enabled: true },
]
</script>

<style scoped></style>
