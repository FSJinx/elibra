<template>
  <div class="flex gap-5 mx-auto max-h-screen overflow-hidden w-full max-w-max p-8 sm:px-6 lg:px-8">
    <!-- Filter -->
    <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm max-h-max w-75">
      <div class="flex items-center justify-between gap-3">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Filters</p>
          <h3 class="mt-2 text-xl font-bold text-slate-950">Refine results</h3>
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-funnel-icon lucide-funnel h-5 w-5 text-slate-400"><path d="M10 20a1 1 0 0 0 .553.895l2 1A1 1 0 0 0 14 21v-7a2 2 0 0 1 .517-1.341L21.74 4.67A1 1 0 0 0 21 3H3a1 1 0 0 0-.742 1.67l7.225 7.989A2 2 0 0 1 10 14z"></path></svg>
      </div>
      <div class="mt-6 space-y-5">
        <div class="space-y-3">
          <p class="text-sm font-semibold text-slate-700">Format</p>
          <div class="flex flex-wrap gap-2">
            <button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">Books</button><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">Journals</button
            ><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">E-books</button><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">Multimedia</button>
          </div>
        </div>
        <div class="space-y-3">
          <p class="text-sm font-semibold text-slate-700">Availability</p>
          <div class="flex flex-wrap gap-2">
            <button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">Available</button><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">On loan</button
            ><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">Reference only</button>
          </div>
        </div>
        <div class="space-y-3">
          <p class="text-sm font-semibold text-slate-700">Campus</p>
          <div class="flex flex-wrap gap-2">
            <button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">Main</button><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">North</button
            ><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">South</button><button type="button" class="rounded-full border border-slate-200 px-3 py-2 text-sm text-slate-600 transition hover:border-emerald-300 hover:text-emerald-700">Satellite</button>
          </div>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <main class="flex flex-col flex-1 space-y-5 max-w-3xl">
      <div class="flex flex-col gap-4 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">
        <div>
          <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Showing results for</p>
          <h3 class="mt-2 text-2xl font-bold text-slate-950">Featured catalog items</h3>
        </div>
        <div class="flex items-center gap-3 text-sm text-slate-500">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-clock3-icon lucide-clock-3 h-4 w-4">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M12 6v6h4"></path>
          </svg>
          Updated just now
        </div>
      </div>
      <div class="items flex flex-col flex-1 overflow-auto gap-5">
        <template v-for="item in items">
          <article class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex flex-col gap-5 sm:flex-row">
              <div class="flex h-28 w-full items-center justify-center rounded-2xl bg-linear-to-br from-emerald-100 to-teal-100 sm:h-36 sm:w-28">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-book-open-icon lucide-book-open h-8 w-8 text-emerald-700">
                  <path d="M12 7v14"></path>
                  <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                </svg>
              </div>
              <div class="min-w-0 flex-1 space-y-4">
                <div>
                  <div class="flex flex-wrap items-center gap-2"><span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">Book</span><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">Available now</span></div>
                  <h4 class="mt-3 text-xl font-bold text-slate-950">Research Methods in Library Science</h4>
                  <p class="mt-1 text-sm text-slate-500">by Dr. Celia Navarro</p>
                </div>
                <p class="text-sm leading-6 text-slate-600">A practical guide to cataloging, collection development, and research workflows for modern academic libraries.</p>
                <div class="flex flex-wrap items-center gap-2"><span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500">Cataloging</span><span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500">Research</span><span class="rounded-full border border-slate-200 px-3 py-1 text-xs text-slate-500">Reference</span></div>
                <div class="flex items-center justify-between gap-3">
                  <p class="text-sm text-slate-500">Main Campus · Stack 3 · Shelf B12</p>
                  <button type="button" class="inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-emerald-600">
                    View details
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-arrow-right-icon lucide-arrow-right h-4 w-4">
                      <path d="M5 12h14"></path>
                      <path d="m12 5 7 7-7 7"></path>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </article>
        </template>
      </div>
    </main>

    <!-- Recent Searches -->
    <aside class="rounded-2xl border border-slate-200 bg-white shadow-sm max-h-150 w-90">
      <div class="flex items-center gap-3 p-6">
        <span class="bg-green-50 border border-green-500 rounded-xl p-2">
          <History class="h-5 w-5 text-primary" />
        </span>
        <div>
          <!-- <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Filters</p> -->
          <h3 class="text-lg font-bold text-slate-950">Recent Searches</h3>
        </div>
      </div>
      <!-- Recent Searches List -->
      <div class="flex flex-col gap-3 overflow-y-auto">
        <template v-for="recent in recents">
          <div class="flex items-center gap-3 text-sm text-gray-500 p-2 px-4">
            <span class="bg-gray-100 rounded-full p-2">
              <History class="h-4 w-4" />
            </span>
            <p>{{ recent.query }}</p>
          </div>
        </template>
      </div>
    </aside>
  </div>
</template>

<script setup lang="ts">
import images from '@/assets/images'
import { ref } from 'vue'

const items = ref([
  { title: 'Research Methods in Library Science', authors: ['Reign', 'Eugene', 'Jef'], item_type: 'book', description: 'This is a sample description for a book that is on the shelf.' },
  { title: 'Research Methods in Library Science', authors: ['Reign', 'Eugene', 'Jef'], item_type: 'book', description: 'This is a sample description for a book that is on the shelf.' },
  { title: 'Research Methods in Library Science', authors: ['Reign', 'Eugene', 'Jef'], item_type: 'book', description: 'This is a sample description for a book that is on the shelf.' },
  { title: 'Research Methods in Library Science', authors: ['Reign', 'Eugene', 'Jef'], item_type: 'book', description: 'This is a sample description for a book that is on the shelf.' },
  { title: 'Research Methods in Library Science', authors: ['Reign', 'Eugene', 'Jef'], item_type: 'book', description: 'This is a sample description for a book that is on the shelf.' },
  { title: 'Research Methods in Library Science', authors: ['Reign', 'Eugene', 'Jef'], item_type: 'book', description: 'This is a sample description for a book that is on the shelf.' },
])

const recents = ref([
  { query: 'Harry Potter', time: 'Just now' },
  { query: 'Noli Me Tangere', time: '5 mins ago' },
  { query: 'General Chemistry', time: '12 mins ago' },
  { query: 'Discrete Mathematics', time: '20 mins ago' },
  { query: 'Research Methodology', time: '37 mins ago' },
  { query: 'Philippine Constitution', time: '1 hour ago' },
  { query: 'Data Structures and Algorithms', time: '2 hours ago' },
  { query: 'Calculus Volume 1', time: 'Yesterday' },
])
</script>

<style scoped>
.items {
  scrollbar-width: none;
}
</style>
