<template>
  <Card class="p-5">
    <h1 class="text-3xl mb-10" v-if="query">
      Result for
      <span class="italic font-bold">"{{ query }}"</span>
    </h1>

    <div class="flex flex-col gap-5">
      <Card class="cursor-pointer">
        <div class="flex items-start gap-4">
          <div class="w-30 rounded shadow border border-slate-300 overflow-hidden">
            <img :src="images.default_book" alt="" class="" />
          </div>
          <div class="flex flex-col p-2 items-start w-full h-full text-sm">
            <h4 class="text-xl font-bold mb-3">Title</h4>
            <p class="text-muted">By: Juan Dela Cruz</p>
            <p class="text-muted">Date Published: 1684</p>
            <p class="text-muted">Description/Abstract: This is a book that has been published to work as a placeholder for the people who will be seeing this website.</p>
          </div>
          <span class="bg-slate-200 text-sm rounded-full px-3.5 py-1">Generals</span>
        </div>
      </Card>
    </div>
  </Card>
</template>

<script setup>
import images from 'src/assets/images'
import Card from '@/components/Card.vue'
  
defineProps({
  query: String,
})
</script>

<style scoped></style>
