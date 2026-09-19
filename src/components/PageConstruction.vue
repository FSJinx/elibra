<template>
  <div class="flex-1 flex size-full">
    <div class="m-auto flex flex-col items-center justify-center text-center gap-2">
      <img :src="construction" alt="" class="size-50 rounded-xl mb-5" />
      <h1 class="font-semibold text-2xl">Page is under construction</h1>
      <p class="mb-10">This page is not yet finished, we'll notify you if this is available.</p>
    </div>
  </div>
</template>

<script setup lang="ts">
import construction from '@/assets/images/construction.gif'
</script>

<style scoped></style>
