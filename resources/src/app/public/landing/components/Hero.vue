<template>
  <div class="flex items-center justify-center max-w-6xl h-screen mx-auto">
    <div class="flex flex-col mb-20">
      <!-- Hero Header -->
      <div class="mb-1">
        <div class="flex justify-center gap-3 mb-5">
          <img :src="images.isu" alt="" class="h-20 w-20" />
          <img :src="images.logo" alt="" class="h-20 w-20" />
        </div>
        <h1 class="mx-auto text-4xl font-bold text-primary text-center mb-3">Isabela State University - Library Services</h1>
        <p class="text-center"><span class="text-primary font-bold">e-Libra</span> is the Library Management System of the whole Isabela State University System, offering centralized services for every ISU-1.</p>
      </div>

      <!-- OPAC Form -->
      <form @submit.prevent="search" class="relative flex items-center px-5 mx-auto mt-5 w-full border border-primary bg-white rounded-full overflow-hidden">
        <input type="text" class="h-15 pl-2 pr-20 w-full outline-0 whitespace-nowrap text-ellipsis" placeholder="Try typing some keywords to search in our OPAC" v-model="query" />
        <button type="submit" class="absolute right-0 flex items-center justify-center mr-2 text-center rounded-full h-[80%] w-auto aspect-square bg-primary text-white cursor-pointer"><i class="fi fi-br-search mt-1.5"></i></button>
      </form>

      <!-- Suggestion Section -->
      <div class="flex items-center justify-center gap-2 text-sm mt-5">
        <span class="font-medium">Try searching for:</span>
        <template v-for="(i, index) in opacHighlights">
          <span class="rounded-full bg-slate-50 border border-primary p-1 px-3 text-primary cursor-pointer" @click="searchTopic(i)">{{ i }}</span>
        </template>
      </div>
    </div>
  </div>
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
}

const opacHighlights = ref(['Books for Programming', 'New Fiction Books', 'IT Capstone Projects', 'Research about Accounting'])
</script>

<style scoped></style>
