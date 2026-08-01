<template>
  <div class="min-h-screen w-full bg-slate-50 text-slate-900">
    <main class="mx-auto w-full max-w-7xl p-8 sm:px-6 lg:px-8">
      <section class="overflow-hidden rounded-4xl border border-slate-200 bg-white shadow-[0_24px_80px_-40px_rgba(15,23,42,0.45)]">
        <div class="grid gap-0 lg:grid-cols-[1.2fr_0.8fr]">
          <div class="relative bg-linear-to-br from-emerald-50 via-white to-teal-50 p-8 sm:p-10 lg:p-12">
            <div class="absolute right-6 top-6 hidden h-28 w-28 rounded-full bg-emerald-200/40 blur-3xl lg:block"></div>
            <div class="absolute bottom-0 left-0 h-40 w-40 rounded-full bg-teal-200/30 blur-3xl"></div>

            <div class="relative space-y-6">
              <span class="inline-flex items-center gap-2 rounded-full border border-emerald-200 bg-white/80 px-4 py-2 text-sm font-medium text-emerald-700 backdrop-blur">
                <Sparkles class="h-4 w-4" />
                Open Access Catalog
              </span>

              <div class="space-y-3">
                <h1 class="max-w-2xl text-4xl font-black tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">Search books, journals, and digital holdings in one place.</h1>
                <p class="max-w-2xl text-base leading-7 text-slate-600 sm:text-lg">Explore the OPAC with fast keyword search, quick filters, and curated collections from every campus library.</p>
              </div>

              <form class="space-y-4" @submit.prevent>
                <div class="flex flex-col gap-3 rounded-3xl border border-slate-200 bg-white p-3 shadow-sm sm:flex-row sm:items-center">
                  <label class="sr-only" for="opac-search">Search the catalog</label>
                  <div class="flex flex-1 items-center gap-3 rounded-2xl bg-slate-50 px-4 py-3 ring-1 ring-transparent transition focus-within:ring-emerald-500/30">
                    <Search class="h-5 w-5 shrink-0 text-slate-400" />
                    <input id="opac-search" v-model="query" type="search" placeholder="Search by title, author, ISBN, or subject" class="w-full bg-transparent text-sm outline-none placeholder:text-slate-400" />
                  </div>

                  <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 font-semibold text-white transition hover:bg-emerald-700">
                    <Search class="h-4 w-4" />
                    Search
                  </button>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                  <button v-for="chip in quickChips" :key="chip" type="button" class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">
                    {{ chip }}
                  </button>
                </div>
              </form>

              <div class="grid gap-4 sm:grid-cols-3">
                <article v-for="stat in stats" :key="stat.label" class="rounded-2xl border border-slate-200 bg-white/80 p-4 shadow-sm backdrop-blur">
                  <p class="text-2xl font-bold text-slate-950">{{ stat.value }}</p>
                  <p class="mt-1 text-sm text-slate-500">{{ stat.label }}</p>
                </article>
              </div>
            </div>
          </div>

          <aside class="border-t border-slate-200 bg-slate-950 p-8 text-white lg:border-l lg:border-t-0 lg:p-10">
            <div class="flex items-center justify-between gap-4">
              <div>
                <p class="text-sm uppercase tracking-[0.25em] text-emerald-300">Today</p>
                <h2 class="mt-2 text-2xl font-bold">Available services</h2>
              </div>
              <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10">
                <ShoppingBag class="h-5 w-5" />
              </span>
            </div>

            <div class="mt-8 space-y-4">
              <article v-for="item in serviceCards" :key="item.title" class="rounded-2xl border border-white/10 bg-white/5 p-5">
                <div class="flex items-start gap-4">
                  <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-emerald-400/15 text-emerald-300">
                    <component :is="item.icon" class="h-5 w-5" />
                  </span>

                  <div class="min-w-0 flex-1">
                    <div class="flex items-center justify-between gap-3">
                      <h3 class="font-semibold text-white">{{ item.title }}</h3>
                      <span class="rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-emerald-200">{{ item.badge }}</span>
                    </div>
                    <p class="mt-2 text-sm leading-6 text-slate-300">{{ item.description }}</p>
                  </div>
                </div>
              </article>
            </div>

            <div class="mt-8 rounded-2xl border border-emerald-400/20 bg-emerald-400/10 p-5">
              <p class="text-sm font-medium text-emerald-200">Pro tip</p>
              <p class="mt-2 text-sm leading-6 text-slate-200">Use broader keywords first, then narrow by format, campus, or availability to get the fastest result.</p>
            </div>
          </aside>
        </div>
      </section>

      <section class="mt-10 grid gap-8 lg:grid-cols-[280px_1fr]">
        <aside class="rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm">
          <div class="flex items-center justify-between gap-3">
            <div>
              <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Filters</p>
              <h3 class="mt-2 text-xl font-bold text-slate-950">Refine results</h3>
            </div>
            <Filter class="h-5 w-5 text-slate-400" />
          </div>

          <div class="mt-6 space-y-5">
            <div v-for="group in filters" :key="group.title" class="space-y-3">
              <p class="text-sm font-semibold text-slate-700">{{ group.title }}</p>
              <div class="flex flex-wrap gap-2">
                <button v-for="option in group.options" :key="option" type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">
                  {{ option }}
                </button>
              </div>
            </div>
          </div>
        </aside>

        <div class="space-y-6">
          <div class="flex flex-col gap-4 rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
            <div>
              <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Results</p>
              <h3 class="mt-2 text-2xl font-bold text-slate-950">Featured catalog items</h3>
            </div>
            <div class="flex items-center gap-3 text-sm text-slate-500">
              <Clock3 class="h-4 w-4" />
              Updated just now
            </div>
          </div>

          <div class="grid gap-5 xl:grid-cols-2">
            <article v-for="book in books" :key="book.title" class="group rounded-[1.75rem] border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
              <div class="flex flex-col gap-5 sm:flex-row">
                <div class="flex h-28 w-full items-center justify-center rounded-2xl bg-linear-to-br from-emerald-100 to-teal-100 sm:h-36 sm:w-28">
                  <BookOpen class="h-8 w-8 text-emerald-700" />
                </div>

                <div class="min-w-0 flex-1 space-y-4">
                  <div>
                    <div class="flex flex-wrap items-center gap-2">
                      <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">{{ book.format }}</span>
                      <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">{{ book.availability }}</span>
                    </div>
                    <h4 class="mt-3 text-xl font-bold text-slate-950">{{ book.title }}</h4>
                    <p class="mt-1 text-sm text-slate-500">{{ book.author }}</p>
                  </div>

                  <p class="text-sm leading-6 text-slate-600">{{ book.description }}</p>

                  <div class="flex flex-wrap items-center gap-2">
                    <span v-for="tag in book.tags" :key="tag" class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500">{{ tag }}</span>
                  </div>

                  <div class="flex items-center justify-between gap-3">
                    <p class="text-sm text-slate-500">{{ book.location }}</p>
                    <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                      View details
                      <ArrowRight class="h-4 w-4" />
                    </button>
                  </div>
                </div>
              </div>
            </article>
          </div>
        </div>
      </section>
    </main>
  </div>
</template>

<script setup lang="ts">
import { ArrowRight, BookOpen, Clock3, Filter, Search, ShoppingBag, Sparkles } from '@lucide/vue'

const query = ref('')

const quickChips = ['Books', 'Journals', 'Theses', 'E-books', 'New arrivals']

const stats = [
  { label: 'Catalog records', value: '12.4K' },
  { label: 'Available today', value: '1.8K' },
  { label: 'Campuses online', value: '7' },
]

const filters = [
  { title: 'Format', options: ['Books', 'Journals', 'E-books', 'Multimedia'] },
  { title: 'Availability', options: ['Available', 'On loan', 'Reference only'] },
  { title: 'Campus', options: ['Main', 'North', 'South', 'Satellite'] },
]

const serviceCards = [
  {
    title: 'Search catalog',
    badge: 'Fast lookup',
    icon: Search,
    description: 'Find materials using title, author, subject, or ISBN with a single search field.',
  },
  {
    title: 'Borrowing status',
    badge: 'Live data',
    icon: Clock3,
    description: 'Show what is available now and surface titles that are currently on loan.',
  },
  {
    title: 'Book bag',
    badge: 'Saved items',
    icon: ShoppingBag,
    description: 'Keep track of titles you want to revisit, reserve, or compare later.',
  },
]

const books = [
  {
    title: 'Research Methods in Library Science',
    author: 'by Dr. Celia Navarro',
    format: 'Book',
    availability: 'Available now',
    description: 'A practical guide to cataloging, collection development, and research workflows for modern academic libraries.',
    location: 'Main Campus · Stack 3 · Shelf B12',
    tags: ['Cataloging', 'Research', 'Reference'],
  },
  {
    title: 'Digital Collections and Preservation',
    author: 'by Adrian Malik',
    format: 'E-book',
    availability: 'On campus access',
    description: 'Covers digital stewardship, metadata, and preservation strategies for institutional repositories and archives.',
    location: 'Online resource · Licensed access',
    tags: ['Digital library', 'Archiving', 'Metadata'],
  },
  {
    title: 'Journals of Campus Learning',
    author: 'University Research Office',
    format: 'Journal',
    availability: 'New issue',
    description: 'Recent research publications and scholarly articles from participating colleges and partner institutions.',
    location: 'North Campus · Periodicals section',
    tags: ['Scholarship', 'Periodicals', 'Recent'],
  },
  {
    title: 'Community Outreach Toolkits',
    author: 'by L. Mendoza & Team',
    format: 'Manual',
    availability: 'Reference only',
    description: 'Toolkit for outreach programs, literacy drives, and service planning across the library network.',
    location: 'South Campus · Reference desk',
    tags: ['Outreach', 'Programs', 'Training'],
  },
]
</script>

<style scoped></style>
