<template>
  <main class="min-h-full bg-slate-50 p-4 text-slate-900 sm:p-6 lg:p-8">
    <div class="mx-auto max-w-7xl space-y-6">
      <section class="overflow-hidden rounded-3xl bg-linear-to-br from-emerald-700 via-emerald-600 to-teal-700 px-6 py-8 text-white shadow-xl shadow-emerald-900/10 sm:px-8 lg:flex lg:items-end lg:justify-between">
        <div class="max-w-2xl">
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-emerald-100">Patron dashboard</p>
          <h1 class="mt-3 text-3xl font-bold tracking-tight sm:text-4xl">Good morning, {{ auth.user?.first_name || 'Reader' }}.</h1>
          <p class="mt-3 max-w-xl text-sm leading-6 text-emerald-50 sm:text-base">Keep track of your loans, discover your next read, and make the most of your library membership.</p>
        </div>

        <router-link :to="{ name: 'OPAC' }" class="mt-6 inline-flex items-center gap-2 self-start rounded-xl bg-white px-4 py-3 text-sm font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-50 lg:mt-0">
          <Icon icon="search" />
          Browse the catalog
        </router-link>
      </section>

      <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article v-for="stat in stats" :key="stat.label" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
          <div class="flex items-start justify-between gap-4">
            <span class="grid size-11 place-items-center rounded-xl" :class="`bg-${stat.color}-50 text-${stat.color}-600`">
              <Icon :icon="stat.icon" />
            </span>
            <span class="text-2xl font-bold text-slate-950">{{ stat.value }}</span>
          </div>
          <p class="mt-4 text-sm font-medium text-slate-500">{{ stat.label }}</p>
        </article>
      </section>

      <section class="grid gap-6 lg:grid-cols-[1.35fr_0.65fr]">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
          <div class="flex items-center justify-between gap-4">
            <div>
              <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Your activity</p>
              <h2 class="mt-2 text-xl font-bold text-slate-950">Loans and due dates</h2>
            </div>
            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">{{ loans.length }} active</span>
          </div>

          <div class="mt-5 divide-y divide-slate-100">
            <article v-for="loan in loans" :key="loan.title" class="flex items-center gap-4 py-4 first:pt-0 last:pb-0">
              <div class="grid size-12 shrink-0 place-items-center rounded-xl bg-emerald-50 text-emerald-700">
                <Icon icon="book" />
              </div>
              <div class="min-w-0 flex-1">
                <h3 class="truncate font-semibold text-slate-900">{{ loan.title }}</h3>
                <p class="mt-1 text-sm text-slate-500">{{ loan.author }}</p>
              </div>
              <div class="text-right">
                <p class="text-sm font-semibold" :class="loan.urgent ? 'text-rose-600' : 'text-slate-700'">{{ loan.due }}</p>
                <p class="mt-1 text-xs text-slate-400">Due date</p>
              </div>
            </article>
          </div>

          <button type="button" class="mt-5 inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:text-emerald-800">
            View borrowing history
            <Icon icon="arrow-right" />
          </button>
        </div>

        <aside class="rounded-2xl bg-slate-950 p-5 text-white shadow-sm sm:p-6">
          <p class="text-xs font-semibold uppercase tracking-[0.18em] text-emerald-300">Explore more</p>
          <h2 class="mt-2 text-xl font-bold">Library services</h2>
          <div class="mt-5 space-y-3">
            <router-link v-for="service in services" :key="service.title" :to="service.to" class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-3 transition hover:border-emerald-300/50 hover:bg-white/10">
              <span class="grid size-10 place-items-center rounded-lg bg-emerald-400/15 text-emerald-300"><Icon :icon="service.icon" /></span>
              <span class="min-w-0 flex-1">
                <span class="block font-semibold">{{ service.title }}</span>
                <span class="mt-1 block text-xs text-slate-400">{{ service.description }}</span>
              </span>
              <Icon icon="arrow-up-right" class="text-slate-400" />
            </router-link>
          </div>
        </aside>
      </section>
    </div>
  </main>
</template>

<script setup lang="ts">
const auth = authStore()

const stats = [
  { icon: 'book', value: 3, label: 'Books currently borrowed', color: 'emerald' },
  { icon: 'clock', value: 1, label: 'Due this week', color: 'amber' },
  { icon: 'bookmark', value: 8, label: 'Saved titles', color: 'sky' },
  { icon: 'check-circle', value: 12, label: 'Books read this year', color: 'violet' },
]

const loans = [
  { title: 'The Design of Everyday Things', author: 'Don Norman', due: 'Due in 2 days', urgent: true },
  { title: 'Atomic Habits', author: 'James Clear', due: 'Due in 9 days', urgent: false },
  { title: 'Introduction to Algorithms', author: 'Cormen, Leiserson, Rivest', due: 'Due in 14 days', urgent: false },
]

const services = [
  { icon: 'search', title: 'Search the catalog', description: 'Find books, journals, and more', to: { name: 'OPAC' } },
  { icon: 'user', title: 'Manage your profile', description: 'Update your account details', to: { name: 'patron.profile' } },
]
</script>

<style scoped></style>
