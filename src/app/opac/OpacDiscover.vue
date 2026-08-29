<template>
  <div class="mt-10">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-3xl text-primary font-bold uppercase tracking-wide">Discover</h1>
      <p class="text-foreground-secondary mt-1">Discover library materials that suit your taste buds.</p>
    </div>

    <!-- Results Grid -->
    <div v-if="paginatedItems.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
      <Card v-for="item in paginatedItems" :key="item.id" class="p-4 hover:shadow-md transition">
        <div class="flex items-start gap-4">
          <!-- Thumbnail -->
          <div class="w-24 h-32 flex-shrink-0 overflow-hidden rounded-md border border-gray-200 dark:border-gray-800 bg-gray-100">
            <img :src="default_book" :alt="item.title" class="size-full object-cover" />
          </div>

          <!-- Book Info -->
          <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between gap-2 mb-1">
              <span class="text-xs uppercase font-semibold px-2 py-0.5 rounded bg-primary/10 text-primary">
                {{ item.category }}
              </span>
              <span class="text-xs font-semibold px-2 py-0.5 rounded capitalize" :class="getStatusClass(item.status)">
                {{ item.status.replace('_', ' ') }}
              </span>
            </div>

            <h2 class="font-bold text-lg text-foreground truncate" :title="item.title">
              {{ item.title }}
            </h2>

            <p class="text-sm text-foreground-secondary mt-0.5 font-medium">
              {{ item.authorOrCreator }}
            </p>

            <div class="mt-3 text-xs text-foreground-secondary space-y-1">
              <p><span class="font-semibold text-foreground">Call No:</span> {{ item.callNumber }}</p>
              <p><span class="font-semibold text-foreground">Publisher:</span> {{ item.publisherOrInstitution }} ({{ item.publicationYear }})</p>
              <p><span class="font-semibold text-foreground">Location:</span> {{ item.location }}</p>
            </div>
          </div>
        </div>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-else class="text-center py-12 border border-dashed rounded-lg border-gray-300 dark:border-gray-700">
      <p class="text-lg font-medium text-foreground-secondary">No library materials found.</p>
      <button @click="resetFilters" class="mt-3 px-4 py-2 text-sm bg-primary text-white rounded-md hover:bg-primary/90 transition">Reset Filters</button>
    </div>

    <!-- Pagination -->
    <div v-if="totalPages > 1" class="mt-8 flex justify-center items-center gap-2">
      <button @click="currentPage--" :disabled="currentPage === 1" class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-800 transition text-sm">Previous</button>

      <span class="text-sm font-medium px-2"> Page {{ currentPage }} of {{ totalPages }} </span>

      <button @click="currentPage++" :disabled="currentPage === totalPages" class="px-3 py-1.5 rounded border border-gray-300 dark:border-gray-700 disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-100 dark:hover:bg-gray-800 transition text-sm">Next</button>
    </div>
  </div>

  <div class="h-screen"></div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import default_book from '@/assets/images/default_book.png'
// Import your Card component if it's not globally registered:
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

// Category Mappings
const CATEGORIES = {
  academic: ['capstone project', 'case study', 'dissertation', 'feasibility study', 'practicum report', 'project study', 'research paper', 'terminal report', 'thesis'],
  book: ['fiction', 'non-fiction', 'novel', 'textbook', 'almanac', 'atlas', 'bibliography', 'dictionary', 'directory', 'encyclopedia', 'thesaurus', 'yearbook', 'filipiniana', 'reserved'],
  serial: ['annual reports', 'journal', 'magazine', 'newspaper', 'newsletter', 'periodicals', 'vertical'],
}

// State
const searchQuery = ref('')
const selectedType = ref<'all' | 'book' | 'academic' | 'serial'>('all')
const selectedCategory = ref('all')
const currentPage = ref(1)
const itemsPerPage = 8

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

// Computed Categories based on selected Type
const availableCategories = computed(() => {
  if (selectedType.value === 'all') {
    return Object.values(CATEGORIES).flat().sort()
  }
  return CATEGORIES[selectedType.value] || []
})

// Reset category if it doesn't match available ones when type changes
watch(selectedType, () => {
  selectedCategory.value = 'all'
  currentPage.value = 1
})

watch([searchQuery, selectedCategory], () => {
  currentPage.value = 1
})

// Filtering logic
const filteredItems = computed(() => {
  return libraryItems.value.filter((item) => {
    const matchesQuery = item.title.toLowerCase().includes(searchQuery.value.toLowerCase()) || item.authorOrCreator.toLowerCase().includes(searchQuery.value.toLowerCase()) || item.callNumber.toLowerCase().includes(searchQuery.value.toLowerCase())

    const matchesType = selectedType.value === 'all' || item.itemType === selectedType.value
    const matchesCategory = selectedCategory.value === 'all' || item.category === selectedCategory.value

    return matchesQuery && matchesType && matchesCategory
  })
})

// Pagination logic
const totalPages = computed(() => Math.ceil(filteredItems.value.length / itemsPerPage) || 1)

const paginatedItems = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage
  return filteredItems.value.slice(start, start + itemsPerPage)
})

// Reset Filters
const resetFilters = () => {
  searchQuery.value = ''
  selectedType.value = 'all'
  selectedCategory.value = 'all'
  currentPage.value = 1
}

// Badge status helper
const getStatusClass = (status: LibraryItem['status']) => {
  switch (status) {
    case 'available':
      return 'bg-green-100 text-green-700 dark:bg-green-950 dark:text-green-300'
    case 'borrowed':
      return 'bg-amber-100 text-amber-700 dark:bg-amber-950 dark:text-amber-300'
    case 'reserved':
      return 'bg-blue-100 text-blue-700 dark:bg-blue-950 dark:text-blue-300'
    default:
      return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'
  }
}
</script>

<style scoped></style>
