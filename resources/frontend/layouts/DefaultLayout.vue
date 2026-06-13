<template>
  <div class="min-h-screen">
    <div class="h-2 bg-primary-dark"></div>
    <Header v-if="withHeader" />
    <main class="min-h-screen max-w-7xl mx-auto px-4 py-8">
      <slot />
    </main>
    <Footer v-if="withFooter" />
  </div>
</template>

<script setup>
import Footer from '@/sections/Footer.vue'
import Header from '@/sections/Header copy.vue'

defineProps({
  withFooter: { type: Boolean, default: true },
  withHeader: { type: Boolean, default: true },
})
</script>
