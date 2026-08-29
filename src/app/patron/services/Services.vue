<template>
  <main class="px-5 pb-24 pt-8 sm:px-8 sm:pb-12">
    <div class="mx-auto max-w-7xl">
      <section class="max-w-2xl">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Library services</p>
        <h1 class="mt-3 text-3xl font-bold tracking-tight text-slate-950 sm:text-4xl">More ways to make the library yours.</h1>
        <p class="mt-3 text-base leading-7 text-slate-500">Everything you need beyond the shelves, gathered in one calm corner.</p>
      </section>
      <section class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        <article v-for="service in services" :key="service.title" class="group flex min-h-48 flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-lg">
          <span class="grid size-11 place-items-center rounded-xl" :class="service.color"><Icon :icon="service.icon" /></span>
          <h2 class="mt-6 font-bold text-slate-950">{{ service.title }}</h2>
          <p class="mt-2 flex-1 text-sm leading-6 text-slate-500">{{ service.description }}</p>
          <button type="button" class="mt-5 inline-flex items-center gap-2 self-start text-sm font-bold text-emerald-700">{{ service.action }} <Icon icon="arrow-right" /></button>
        </article>
      </section>
      <section class="mt-8 flex flex-col gap-4 rounded-2xl bg-emerald-900 p-6 text-white sm:flex-row sm:items-center sm:justify-between sm:p-8">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.18em] text-emerald-300">Need a hand?</p>
          <h2 class="mt-2 text-xl font-bold">Ask the library team</h2>
          <p class="mt-1 text-sm text-emerald-100">We usually reply within one working day.</p>
        </div>
        <button type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-4 py-3 text-sm font-bold text-emerald-900"><Icon icon="chat-dots" /> Contact us</button>
      </section>
    </div>
  </main>
</template>

<script setup lang="ts">
const services = [
  { icon: 'bookmark-plus', title: 'Place a hold', description: 'Reserve a title and choose where you would like to collect it.', action: 'Reserve a book', color: 'bg-amber-50 text-amber-700' },
  { icon: 'arrow-repeat', title: 'Renew a loan', description: 'Keep your current reading a little longer when no one is waiting.', action: 'Review loans', color: 'bg-sky-50 text-sky-700' },
  { icon: 'question-circle', title: 'Research help', description: 'Get guidance finding sources, databases, and academic materials.', action: 'Request help', color: 'bg-violet-50 text-violet-700' },
  { icon: 'laptop', title: 'Digital library', description: 'Access subscribed journals, ebooks, and learning resources.', action: 'Open resources', color: 'bg-emerald-50 text-emerald-700' },
  { icon: 'calendar-event', title: 'Library events', description: 'See workshops, orientations, and community activities coming up.', action: 'View events', color: 'bg-rose-50 text-rose-700' },
  { icon: 'building', title: 'Visit a branch', description: 'Check opening hours, directions, and services at each location.', action: 'Find a branch', color: 'bg-orange-50 text-orange-700' },
]
</script>
