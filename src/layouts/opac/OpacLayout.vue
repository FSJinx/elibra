<template>
  <div class="min-h-dvh w-full">
    <Header />

    <main class="scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/25 transition-all duration-300">
      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import OpacHeader from '@/layouts/opac/OpacHeader.vue'
</script>

<style scoped></style>
