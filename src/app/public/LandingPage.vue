<template>
  <MainLayout>
    <NewHero />
    <section class="h-screen"></section>
  </MainLayout>
</template>

<script setup lang="ts">
import Hero from '@/app/public/components/landing/Hero.vue'
import NewHero from '@/app/public/components/landing/NewHero.vue'
import MainLayout from '@/layouts/MainLayout.vue'
</script>

<style scoped></style>
