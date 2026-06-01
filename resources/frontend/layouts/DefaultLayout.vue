<template>
  <Header />
  <main class="min-h-screen pb-80 overflow-x-hidden">
    <slot />
  </main>
  <Footer />
</template>

<script setup>
import Footer from '@/sections/Footer.vue'
import Header from '@/sections/Header.vue'
</script>
