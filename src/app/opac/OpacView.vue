```vue
<template>
  <div class="min-h-screen w-full max-w-7xl mx-auto bg-background border-x border-border shadow">
    <!-- Header -->
    <div class="sticky top-0 z-10 flex items-center px-5 py-5 border-b border-border bg-background/95 backdrop-blur">
      <Button icon="arrow-left" data-title="Return" variant="text" @click="$router.back()" />

      <div class="ml-4 min-w-0">
        <p class="text-xs text-foreground-secondary uppercase tracking-wider">Online Public Access Catalog</p>

        <h5 class="font-medium text-lg sm:text-2xl truncate">Now Viewing: Clean Code</h5>
      </div>
    </div>

    <!-- Main Content -->
    <main class="p-6 sm:p-10">
      <!-- Book Overview -->
      <section class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-8 lg:gap-12">
        <!-- Cover -->
        <div class="flex justify-center lg:justify-start max-w-100 shrink-0">
          <div class="relative w-full h-max rounded-2xl border-2 border-border shadow-lg overflow-hidden group contain-content">
            <img :src="default_book" alt="Clean Code book cover" class="w-full h-auto object-cover transition-transform duration-500 group-hover:scale-105" />

            <!-- Availability Badge -->
            <div class="absolute top-0 left-0 size-full group hover:bg-backdrop/50 transition-all duration-300">
              <span class="inline-flex items-center gap-2 px-3 py-1.5 m-5 rounded-full bg-background/95 backdrop-blur text-sm font-medium shadow opacity-0 group-hover:opacity-100 transition-all duration-300">
                <span class="size-2 rounded-full bg-green-500"></span>
                Available
              </span>
            </div>
          </div>
        </div>

        <!-- Information -->
        <div class="flex flex-col">
          <!-- Title -->
          <div class="mb-6">
            <div class="flex flex-wrap items-center gap-2 mb-3">
              <span class="px-2.5 py-1 rounded-md bg-primary/10 text-primary text-xs font-semibold uppercase"> Book </span>

              <span class="px-2.5 py-1 rounded-md bg-background border border-border text-xs"> 3rd Edition </span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-semibold tracking-tight leading-tight">Clean Code</h1>

            <p class="mt-2 text-xl text-foreground-secondary">A Handbook of Agile Software Craftsmanship</p>

            <p class="mt-4 text-sm">
              by
              <span class="font-medium text-primary"> Robert C. Martin </span>
            </p>
          </div>

          <!-- Quick Metadata -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-7">
            <div class="p-4 rounded-xl border border-border bg-background-secondary">
              <p class="text-xs text-foreground-secondary mb-1">Publication</p>
              <p class="font-medium">2008</p>
            </div>

            <div class="p-4 rounded-xl border border-border bg-background-secondary">
              <p class="text-xs text-foreground-secondary mb-1">Publisher</p>
              <p class="font-medium">Prentice Hall</p>
            </div>

            <div class="p-4 rounded-xl border border-border bg-background-secondary">
              <p class="text-xs text-foreground-secondary mb-1">Pages</p>
              <p class="font-medium">464</p>
            </div>

            <div class="p-4 rounded-xl border border-border bg-background-secondary">
              <p class="text-xs text-foreground-secondary mb-1">Language</p>
              <p class="font-medium">English</p>
            </div>
          </div>

          <!-- Availability -->
          <div class="rounded-xl border border-border p-5 mb-6">
            <div class="flex items-center justify-between mb-4">
              <div>
                <h3 class="font-semibold text-lg">Availability</h3>

                <p class="text-sm text-foreground-secondary">Current copies in the library</p>
              </div>

              <span class="text-sm font-medium text-green-600"> 3 of 5 copies available </span>
            </div>

            <div class="space-y-3">
              <div class="flex items-center justify-between p-3 rounded-lg bg-background-secondary">
                <div class="flex items-center gap-3">
                  <span class="size-9 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center">
                    <i class="icon-book-open text-sm"></i>
                  </span>

                  <div>
                    <p class="font-medium text-sm">Main Campus Library</p>

                    <p class="text-xs text-foreground-secondary">General Collection · Shelf A-12</p>
                  </div>
                </div>

                <span class="text-xs font-medium text-green-600"> Available </span>
              </div>

              <div class="flex items-center justify-between p-3 rounded-lg bg-background-secondary">
                <div class="flex items-center gap-3">
                  <span class="size-9 rounded-lg bg-green-500/10 text-green-600 flex items-center justify-center">
                    <i class="icon-book-open text-sm"></i>
                  </span>

                  <div>
                    <p class="font-medium text-sm">Main Campus Library</p>

                    <p class="text-xs text-foreground-secondary">General Collection · Shelf A-12</p>
                  </div>
                </div>

                <span class="text-xs font-medium text-green-600"> Available </span>
              </div>

              <div class="flex items-center justify-between p-3 rounded-lg bg-background-secondary">
                <div class="flex items-center gap-3">
                  <span class="size-9 rounded-lg bg-red-500/10 text-red-600 flex items-center justify-center">
                    <i class="icon-book text-sm"></i>
                  </span>

                  <div>
                    <p class="font-medium text-sm">Main Campus Library</p>

                    <p class="text-xs text-foreground-secondary">General Collection · Shelf A-12</p>
                  </div>
                </div>

                <span class="text-xs font-medium text-red-600"> Borrowed </span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex flex-wrap gap-3">
            <Button left-icon="bookmark" variant="primary"> Reserve Book </Button>

            <Button left-icon="heart"> Add to Favorites </Button>

            <Button left-icon="share-2" variant="text"> Share </Button>
          </div>
        </div>
      </section>

      <!-- Divider -->
      <div class="border-t border-border my-10"></div>

      <!-- Breadcrumb -->
      <div class="flex items-center gap-2 text-sm text-foreground-secondary my-10">
        <router-link class="" to="">Details</router-link>
        <router-link class="" to="">Copies</router-link>
      </div>

      <!-- Details -->
      <section class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Description -->
        <div class="lg:col-span-2">
          <h2 class="text-2xl font-semibold mb-4">About this item</h2>

          <p class="leading-7 text-foreground-secondary">Even bad code can function. But if code isn't clean, it can bring a development organization to its knees. Every year, countless hours and significant resources are lost because of poorly written code. This book presents practical principles and techniques for writing clean, readable, maintainable, and flexible software.</p>

          <p class="mt-4 leading-7 text-foreground-secondary">Written by software craftsmanship expert Robert C. Martin, this handbook provides examples of code transformations and explains how professional developers can produce better software through discipline, craftsmanship, and continuous improvement.</p>

          <!-- Subjects -->
          <div class="mt-7">
            <h3 class="font-semibold mb-3">Subjects</h3>

            <div class="flex flex-wrap gap-2">
              <span class="px-3 py-1.5 rounded-lg border border-border text-sm"> Software Engineering </span>

              <span class="px-3 py-1.5 rounded-lg border border-border text-sm"> Programming </span>

              <span class="px-3 py-1.5 rounded-lg border border-border text-sm"> Agile Development </span>

              <span class="px-3 py-1.5 rounded-lg border border-border text-sm"> Software Craftsmanship </span>

              <span class="px-3 py-1.5 rounded-lg border border-border text-sm"> Code Quality </span>
            </div>
          </div>
        </div>

        <!-- Bibliographic Information -->
        <aside>
          <div class="rounded-xl border border-border p-5">
            <h3 class="font-semibold text-lg mb-5">Bibliographic Information</h3>

            <dl class="space-y-4 text-sm">
              <div>
                <dt class="text-foreground-secondary">ISBN</dt>

                <dd class="font-medium mt-1">978-0132350884</dd>
              </div>

              <div>
                <dt class="text-foreground-secondary">Call Number</dt>

                <dd class="font-medium mt-1">QA76.76.C672 M37</dd>
              </div>

              <div>
                <dt class="text-foreground-secondary">Accession Number</dt>

                <dd class="font-medium mt-1">ISU-2026-000184</dd>
              </div>

              <div>
                <dt class="text-foreground-secondary">Classification</dt>

                <dd class="font-medium mt-1">Computer Science</dd>
              </div>

              <div>
                <dt class="text-foreground-secondary">Material Type</dt>

                <dd class="font-medium mt-1">Printed Book</dd>
              </div>

              <div>
                <dt class="text-foreground-secondary">Collection</dt>

                <dd class="font-medium mt-1">General Collection</dd>
              </div>
            </dl>
          </div>
        </aside>
      </section>

      <!-- Footer Note -->
      <div class="mt-10 p-5 rounded-xl bg-primary/5 border border-primary/10">
        <div class="flex gap-3">
          <span class="shrink-0 size-9 rounded-lg bg-primary/10 text-primary flex items-center justify-center">
            <i class="icon-info text-sm"></i>
          </span>

          <div>
            <p class="font-medium text-sm">Need help finding this item?</p>

            <p class="text-sm text-foreground-secondary mt-1">Ask a librarian at the Main Campus Library for assistance locating this material.</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup lang="ts">
import default_book from '@/assets/images/default_book.png'

const pages = ref([
  { name: 'Details', path: '' },
  { name: 'Copies', path: '' },
])
</script>

<style scoped></style>
