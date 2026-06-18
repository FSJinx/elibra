<template>
  <div class="">
    <div class="mb-5">
      <div class="flex justify-center gap-3 mb-5">
        <img :src="images.isu" alt="" class="h-20 w-20" />
        <img :src="images.logo" alt="" class="h-20 w-20" />
      </div>
      <h1 class="mx-auto text-4xl font-extrabold text-primary text-center mb-3">Isabela State University - Library Services</h1>
      <p class="text-center"><span class="text-primary font-bold">e-Libra</span> is the Library Management System of the whole Isabela State University System, offering centralized services for every ISU-1.</p>
    </div>

    <form @submit.prevent="search" class="relative flex items-center px-5 mx-auto mt-5 max-w-6xl border border-primary bg-white rounded-full overflow-hidden">
      <input type="text" class="h-17 pl-2 pr-20 w-full outline-0 whitespace-nowrap text-ellipsis" placeholder="Try typing some keywords to search in our OPAC" v-model="query" />
      <button type="submit" class="absolute right-0 mr-2 text-center rounded-full h-[80%] w-auto aspect-square bg-primary text-white"><Search class="m-auto" /></button>
    </form>

    <div class="flex items-center justify-center gap-2 text-sm mt-5">
      <template v-for="(i, index) in opacHighlights">
        <a href="#" class="text-secondary underline">{{ i }}</a>
        <span v-if="index !== opacHighlights.length - 1">|</span>
      </template>
    </div>
  </div>
</template>

<script setup>
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

const opacHighlights = ref(['Semantic Search Engine', 'Advanced Search Filters', 'Easy Searching', 'AI Feature'])
</script>

<style scoped></style>
