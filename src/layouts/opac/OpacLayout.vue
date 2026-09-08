<template>
  <div class="flex flex-col h-dvh overflow-hidden">
    <FeatureBelt />
    <Header />

    <div class="flex-1 overflow-y-auto bg-secondary">
      <router-view />
    </div>
  </div>
</template>

<script setup lang="ts">
import MainLayout from '@/layouts/MainLayout.vue'
</script>

<style scoped></style>
