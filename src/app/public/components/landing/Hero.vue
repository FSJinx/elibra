<template>
  <div class="relative w-full max-w-[100rem] p-10 my-10 space-y-5 mx-auto">
    <!-- ISU Header -->
    <div class="flex flex-col items-center font-baskervville text-center sm:text-3xl">
      <div class="flex items-center justify-center gap-2 mb-5 sm:mb-7">
        <img :src="isu" alt="" class="size-15 sm:size-25 z-2 rounded-full" />

        <div class="absolute size-15 sm:size-25 z-1 rounded-full bg-primary/25 blur-md"></div>
      </div>

      <p class="font-medium text-[75%]">The Online Public Access Catalog (OPAC) of the</p>

      <div class="text-[160%] uppercase font-semibold text-green-700 tracking-tight"><span class="text-[125%]">I</span>sabela <span class="text-[125%]">S</span>tate <span class="text-[125%]">U</span>niversity</div>
    </div>

    <!-- Statement of the System -->
    <div class="max-w-5xl mx-auto text-center px-5">
      <Chip class="px-2 rounded-md">e-Libra</Chip>
      is the official Integrated Library Management System of the Isabela State University. Developed by FSJinx,
      <Chip class="px-2 rounded-md">e-Libra</Chip>
      acts a centralized database for library/academic materials for all Isabela State University Students.
    </div>

    <!-- Search Box -->
    <div>
      <Form @submit="search" class="flex h-10 sm:h-16 w-full max-w-4xl mx-auto rounded-xl border border-border focus-within:border-primary focus-within:ring-5 focus-within:ring-primary/20 overflow-hidden transition-all duration-300">
        <Button class="rounded-none! bg-slate-50 h-full sm:text-lg hover:text-primary" variant="text" left-icon="sliders2"> Advanced </Button>

        <input required id="opac-search" v-model="opac.search" type="text" class="flex-1 px-5 sm:text-lg bg-background leading-0 border-x border-border outline-none" placeholder="Search for title, author, or call number..." autocomplete="off" />

        <Button type="submit" class="rounded-none! bg-slate-50 h-full sm:text-lg hover:text-primary" variant="text" left-icon="search"> Search </Button>
      </Form>

      <!-- Search Recommendations -->
      <div class="flex gap-5 items-center max-w-max mx-auto px-5 my-5 text-foreground-secondary">
        <p class="">You can try searching for:</p>

        <Transition name="search-helper" mode="out-in">
          <span :key="quickSearch" @mouseenter="pauseSearchRotation" @mouseleave="resumeSearchRotation" class="inline-block bg-background border border-border rounded-lg shadow text-sm px-3 py-1 text-primary text-center capitalize cursor-pointer" :data-title="`Click to search ${quickSearch} in the OPAC.`">
            {{ quickSearch }}
          </span>
        </Transition>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import isu from '@/assets/images/isu.png'

let searchIndex = 0
let searchInterval: ReturnType<typeof setInterval> | null = null
const quickSearch = ref('')
const opac = reactive({
  search: '',
  campus: '',
  item_type: '',
})

const possibleSearches = ['books about programming', 'researches about fish', 'books about Philippine history', 'novels by Filipino authors', 'books about information technology']

function search() {
  return router.push({ name: 'opac', query: opac })
}

function nextSearchRecommendation() {
  quickSearch.value = possibleSearches[searchIndex]

  searchIndex = (searchIndex + 1) % possibleSearches.length
}

function startSearchRotation() {
  if (searchInterval) return

  searchInterval = setInterval(() => {
    nextSearchRecommendation()
  }, 5000)
}

function pauseSearchRotation() {
  if (searchInterval) {
    clearInterval(searchInterval)
    searchInterval = null
  }
}

function resumeSearchRotation() {
  startSearchRotation()
}

onMounted(() => {
  nextSearchRecommendation()
  startSearchRotation()
})

onUnmounted(() => {
  pauseSearchRotation()
})
</script>

<style scoped>
.search-helper-enter-active,
.search-helper-leave-active {
  transition:
    opacity 0.3s ease,
    transform 0.3s ease;
}

.search-helper-enter-from {
  opacity: 0;
  transform: translateY(8px);
}

.search-helper-leave-to {
  opacity: 0;
  transform: translateY(-8px);
}
</style>
