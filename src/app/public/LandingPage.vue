<template>
  <MainLayout>
    <div class="w-full max-w-[100rem] px-10 mx-auto">
      <Hero />
      <NewItems />
      <Discover />
    </div>
  </MainLayout>
</template>

<script setup lang="ts">
import Discover from '@/app/public/components/landing/Discover.vue'
import Hero from '@/app/public/components/landing/Hero.vue'
import NewItems from '@/app/public/components/landing/NewItems.vue'
import MainLayout from '@/layouts/MainLayout.vue'
</script>

<style scoped></style>
