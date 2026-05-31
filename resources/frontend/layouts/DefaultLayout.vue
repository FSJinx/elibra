<template>
  <Header />
  <main class="min-h-screen pb-80 pt-18 overflow-x-hidden">
    <slot />
  </main>
  <Footer />
</template>

<script setup>
import Header from '@/components/public/Header.vue'
import Footer from '@/components/public/Footer.vue'
</script>
