<template>
  <section class="relative overflow-hidden bg-radial from-primary/5 via-transparent to-transparent">
    <div class="place-content-center w-full max-w-7xl mx-auto px-6 py-16 sm:pb-24 sm:pt-20 space-y-8 mb-20">
      <!-- Logo Branding -->
      <div class="flex items-center justify-center">
        <IsuLogo width="85" class="drop-shadow-lg drop-shadow-primary/50" />
      </div>

      <!-- Typography -->
      <div class="space-y-4 max-w-4xl mx-auto text-center">
        <h1 class="text-4xl sm:text-6xl font-extrabold text-primary tracking-tight leading-none">
          Isabela State University
          <span class="block text-secondary mt-2 text-3xl sm:text-5xl font-medium">Library Services</span>
        </h1>
        <p class="text-base sm:text-lg text-secondary/80 max-w-2xl mx-auto leading-relaxed">Welcome to <Chip>e-Libra</Chip>, the centralized Library Management System empowering academic discovery for every ISU-1.</p>
      </div>

      <!-- OPAC Search Form -->
      <div class="max-w-3xl mx-auto w-full">
        <form @submit.prevent="search" class="group relative flex items-center p-2 w-full border border-secondary/10 bg-white/80 backdrop-blur-md rounded-full shadow-lg hover:shadow-xl focus-within:border-primary/50 focus-within:ring-4 focus-within:ring-primary/10 transition-all duration-300">
          <div class="pl-4 pr-2 text-secondary/40 group-focus-within:text-primary transition-colors">
            <Icon icon="search" />
            <!-- <i class="fi fi-br-search text-xl"></i> -->
          </div>
          <input type="text" class="h-14 w-full outline-none text-base sm:text-lg text-secondary placeholder-secondary/50 pr-4 bg-transparent" placeholder="Search books, journals, capstones, and repositories..." v-model="query" />
          <button type="submit" class="flex items-center justify-center px-6 mr-2 h-12 rounded-full bg-primary text-white font-medium hover:bg-primary-dark shadow-md hover:shadow-primary/20 active:scale-95 transition-all cursor-pointer">Search</button>
        </form>

        <!-- Dynamic Suggested Topics (Utilizing your script setup) -->
        <div class="mt-5 flex flex-wrap items-center justify-center gap-2 text-sm">
          <span class="text-secondary/60 font-medium">Popular:</span>
          <button v-for="topic in opacHighlights" :key="topic" @click="searchTopic(topic)" type="button" class="px-3 py-1 bg-secondary/5 hover:bg-primary/10 text-secondary/80 hover:text-primary border border-secondary/5 hover:border-primary/20 rounded-full transition-all duration-200 cursor-pointer text-xs sm:text-sm">
            {{ topic }}
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import images from '@/assets/images'
import router from '@/router'
import { ref } from 'vue'

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
