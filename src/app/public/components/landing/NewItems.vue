<template>
  <div class="my-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl text-warning font-bold uppercase tracking-wide">What's New?</h1>
      <p class="text-foreground-secondary mt-1">Discover recently added library materials for your research and leisure.</p>
    </div>

    <!-- Results Grid -->
    <div v-if="paginatedItems.length > 0" class="grid grid-cols-1 xl:grid-cols-2 gap-4">
      <Card v-for="item in paginatedItems" :key="item.id" class="p-4 hover:shadow-md transition-shadow border border-border bg-background rounded-xl">
        <div class="flex items-start gap-4">
          <!-- Book Cover / Thumbnail -->
          <div class="w-24 h-32 shrink-0 overflow-hidden rounded-md border border-border bg-muted">
            <img :src="default_book" :alt="item.title" class="size-full object-cover" />
          </div>

          <!-- Book Metadata -->
          <div class="flex-1 min-w-0 flex flex-col justify-between self-stretch">
            <div>
              <div class="flex items-center justify-between gap-2 mb-1.5">
                <span class="text-[10px] uppercase font-bold tracking-wider px-2 py-0.5 rounded bg-primary/10 text-primary truncate">
                  {{ item.category }}
                </span>
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded capitalize shrink-0" :class="getStatusClass(item.status)">
                  {{ item.status.replace('_', ' ') }}
                </span>
              </div>

              <h2 class="font-bold text-base text-foreground line-clamp-1" :title="item.title">
                {{ item.title }}
              </h2>

              <p class="text-xs text-foreground-secondary mt-0.5 font-medium line-clamp-1">
                {{ item.authorOrCreator }}
              </p>
            </div>

            <!-- Detailed Info -->
            <div class="mt-3 text-xs text-foreground-secondary space-y-0.5 border-t border-border/50 pt-2">
              <p class="truncate"><span class="font-semibold text-foreground">Call No:</span> {{ item.callNumber }}</p>
              <p class="truncate"><span class="font-semibold text-foreground">Publisher:</span> {{ item.publisherOrInstitution }} ({{ item.publicationYear }})</p>
              <p class="truncate"><span class="font-semibold text-foreground">Location:</span> {{ item.location }}</p>
            </div>
          </div>
        </div>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12 border border-dashed rounded-xl border-border">
      <p class="text-base font-medium text-foreground-secondary">No new library materials found.</p>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="mt-8 flex justify-center items-center gap-3">
      <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 rounded-lg border border-border bg-background disabled:opacity-50 disabled:cursor-not-allowed hover:bg-muted transition text-xs font-medium">Previous</button>

      <span class="text-xs font-semibold px-2 text-foreground-secondary"> Page {{ currentPage }} of {{ totalPages }} </span>

      <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1.5 rounded-lg border border-border bg-background disabled:opacity-50 disabled:cursor-not-allowed hover:bg-muted transition text-xs font-medium">Next</button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import default_book from '@/assets/images/default_book.png'
// import Card from '@/components/ui/Card.vue'

export interface LibraryItem {
  id: string
  title: string
  itemType: 'book' | 'academic' | 'serial'
  category: string
  authorOrCreator: string
  callNumber: string
  publicationYear: number
  publisherOrInstitution: string
  status: 'available' | 'borrowed' | 'reserved' | 'in_maintenance'
  location: string
}

// State
const currentPage = ref(1)
const itemsPerPage = 6

// Sample Library Data
const libraryItems = ref<LibraryItem[]>([
  {
    id: 'LIB-ACAD-001',
    title: 'AI-Powered Microgrid Optimization for Rural Barangays',
    itemType: 'academic',
    category: 'capstone project',
    authorOrCreator: 'Dela Cruz, Juan & Santos, Maria',
    callNumber: 'CP 621.31 D37 2024',
    publicationYear: 2024,
    publisherOrInstitution: 'College of Engineering',
    status: 'available',
    location: 'Academic Archives - Shelf A3',
  },
  {
    id: 'LIB-ACAD-003',
    title: 'Socio-Economic Impacts of Ecotourism in Coastal Communities',
    itemType: 'academic',
    category: 'dissertation',
    authorOrCreator: 'Dr. Alcantara, Sofia P.',
    callNumber: 'DIS 338.47 AL15 2022',
    publicationYear: 2022,
    publisherOrInstitution: 'Graduate School',
    status: 'available',
    location: 'Graduate Research Room',
  },
  {
    id: 'LIB-BOOK-001',
    title: 'The Silent Patient',
    itemType: 'book',
    category: 'fiction',
    authorOrCreator: 'Alex Michaelides',
    callNumber: 'FIC Mic 2019',
    publicationYear: 2019,
    publisherOrInstitution: 'Celadon Books',
    status: 'borrowed',
    location: 'General Fiction - Shelf 12',
  },
  {
    id: 'LIB-BOOK-003',
    title: 'Noli Me Tangere',
    itemType: 'book',
    category: 'filipiniana',
    authorOrCreator: 'José Rizal',
    callNumber: 'FIL 899.21 R52n 1996',
    publicationYear: 1996,
    publisherOrInstitution: 'National Book Store',
    status: 'available',
    location: 'Filipiniana Section - Cabinet 01',
  },
  {
    id: 'LIB-SER-001',
    title: 'IEEE Transactions on Software Engineering - Vol. 50 No. 2',
    itemType: 'serial',
    category: 'journal',
    authorOrCreator: 'IEEE Computer Society',
    callNumber: 'PER 005.1 I27 2024',
    publicationYear: 2024,
    publisherOrInstitution: 'IEEE',
    status: 'available',
    location: 'Serials Section - Rack 05',
  },
  {
    id: 'LIB-SER-002',
    title: 'National Geographic - March 2024 Edition',
    itemType: 'serial',
    category: 'magazine',
    authorOrCreator: 'National Geographic Society',
    callNumber: 'PER 910 N21 2024-03',
    publicationYear: 2024,
    publisherOrInstitution: 'National Geographic Partners',
    status: 'reserved',
    location: 'Serials Display',
  },
])

// Automatically sort items by publication year descending ("What's New")
const sortedItems = computed(() => {
  return [...libraryItems.value].sort((a, b) => b.publicationYear - a.publicationYear)
})

// Pagination logic
const totalPages = computed(() => Math.ceil(sortedItems.value.length / itemsPerPage) || 1)

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return sortedItems.value.slice(start, start + itemsPerPage)
})

// Badge status color mapping
const getStatusClass = (status: LibraryItem['status']) => {
  switch (status) {
    case 'available':
      return 'bg-green-100 text-green-700 dark:bg-green-950/60 dark:text-green-400'
    case 'borrowed':
      return 'bg-amber-100 text-amber-700 dark:bg-amber-950/60 dark:text-amber-400'
    case 'reserved':
      return 'bg-blue-100 text-blue-700 dark:bg-blue-950/60 dark:text-blue-400'
    default:
      return 'bg-muted text-foreground-secondary'
  }
}
</script>

<style scoped></style>
