<template>
  <section class="relative flex flex-col gap-5 justify-center overflow-hidden min-h-[90dvh] pb-30 w-full max-w-5xl mx-auto bg-radial from-primary/5 via-transparent to-transparent">
    <!-- Logo Branding -->
    <div class="flex items-center justify-center text-5xl sm:text-[6rem]">
      <IsuLogo />
    </div>

    <!-- Typography -->
    <div class="space-y-5 mx-auto text-center">
      <h1 class="font-baskervville uppercase text-2xl sm:text-5xl font-extrabold text-primary tracking-tight leading-none">
        <span class="text-[125%]">I</span>sabela <span class="text-[125%]">S</span>tate <span class="text-[125%]">U</span>niversity
        <span class="block mt-2 text-xl sm:text-4xl"><span class="text-[125%]">L</span>ibrary <span class="text-[125%]">S</span>ervices</span>
      </h1>
      <p class="text-foreground-secondary font-light max-w-2xl mx-auto leading-relaxed">Welcome to <Chip>e-Libra</Chip>, the centralized Library Management System empowering academic discovery for every ISU-1.</p>
    </div>

    <!-- OPAC Search Form -->
    <div class="max-w-2xl mx-auto w-full">
      <form @submit.prevent="search" class="group relative flex items-center py-1 px-5 gap-4 w-full border border-border bg-white/80 backdrop-blur-md rounded-xl shadow hover:shadow-md focus-within:border-primary/50 focus-within:ring-4 focus-within:ring-primary/10 transition-all duration-300">
        <span class="text-foreground-secondary group-focus-within:text-primary transition-colors">
          <Icon icon="search" />
        </span>
        <input type="text" class="h-14 w-full outline-none text-base text-foreground placeholder-foreground/50 bg-transparent" placeholder="Search books, journals, capstones, and repositories..." v-model="query" />
        <span class="shrink-0 text-sm text-foreground-secondary">Ctrl + K</span>
        <!-- <button type="submit" class="flex items-center justify-center px-6 mr-2 h-12 rounded-full bg-primary text-white font-medium hover:bg-primary-dark shadow-md hover:shadow-primary/20 active:scale-95 transition-all cursor-pointer">Search</button> -->
      </form>

      <!-- Dynamic Suggested Topics (Utilizing your script setup) -->
      <div class="mt-5 flex flex-wrap items-center justify-center gap-2 text-sm">
        <span class="text-primary font-medium">Popular:</span>
        <button v-for="topic in opacHighlights" :key="topic" @click="searchTopic(topic)" type="button" class="px-3 py-1 bg-secondary hover:bg-primary-soft/50 text-foreground hover:text-primary border border-border hover:border-primary/20 rounded-full transition-all duration-200 cursor-pointer text-xs sm:text-sm">
          {{ topic }}
        </button>
      </div>
    </div>
    <!-- <div class="place-content-center w-full px-6 space-y-5">
    </div> -->
  </section>
</template>

<script setup lang="ts">
const query = ref('')

function search() {
  if (!query.value) return
  router.push({
    name: 'OPAC',
    query: {
      q: query.value,
      auth: 'user',
    },
  })
}

function searchTopic(topic: string) {
  query.value = topic
  search() // Added this so it automatically fires the search when a tag is clicked!
}

const opacHighlights = ref(['Books for Programming', 'New Fiction Books', 'IT Capstone Projects', 'Research about Accounting'])
</script>

<style scoped></style>
