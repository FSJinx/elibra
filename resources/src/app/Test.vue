<template>
  <div class="inset-0 fixed flex flex-col top-0 left-0 h-screen max-w-3xs">
    <h1 class="text-2xl font-bold p-5 text-shadow-md hover:text-shadow-secondary/50 transition-all duration-300">Components</h1>
    <span v-for="page in pages" class="relative flex items-center capitalize rounded-full rounded-tl-none rounded-bl-none overflow-hidden transition-all duration-150 cursor-pointer" :class="[currentPage === page ? 'text-white ' : ' hover:bg-slate-100']" @click="setPage(page)">
      <span class="p-5 z-1">{{ page }}</span>
      <div class="absolute left-0 h-full bg-secondary transition-all duration-200" :class="[currentPage === page ? 'w-full' : 'w-0']"></div>
    </span>
  </div>
  <div class="divide-y divide-slate-400 space-y-5 max-w-2xl mx-auto p-5">
    <TestButton v-if="currentPage === 'buttons'" />
    <TestInput v-if="currentPage === 'inputs'" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import TestButton from './test/TestButton.vue'
import TestInput from './test/TestInput.vue'

const pages = ['buttons', 'inputs']
const currentPage = ref(localStorage.getItem('test') ?? 'buttons')

const setPage = (page: string) => {
  currentPage.value = page
  localStorage.setItem('test', page)
}
</script>

<style scoped></style>
