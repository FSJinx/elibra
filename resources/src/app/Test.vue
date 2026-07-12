<template>
  <div class="inset-0 fixed flex flex-col top-0 left-0 h-screen bg-transparent max-w-xs">
    <h1 class="text-2xl font-bold p-5 text-shadow-md hover:text-shadow-secondary/50 transition-all duration-300">Components</h1>
    <span v-for="page in pages" class="p-5 py-5 capitalize transition-all duration-150 cursor-pointer" :class="[currentPage === page ? 'bg-secondary/80 text-white hover:bg-secondary/80' : ' hover:bg-slate-200']" @click="setPage(page)">{{ page }}</span>
  </div>
  <div class="divide-y divide-slate-400 space-y-5 max-w-lg mx-auto p-5">
    <TestButton v-if="currentPage === 'buttons'" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import TestButton from './test/TestButton.vue'

const pages = ['buttons', 'inputs']
const currentPage = ref(localStorage.getItem('test') ?? 'buttons')

const setPage = (page: string) => {
  currentPage.value = page
  localStorage.setItem('test', page)
}
</script>

<style scoped></style>
