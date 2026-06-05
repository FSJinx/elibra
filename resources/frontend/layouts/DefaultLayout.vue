<template>
  <Header v-if="withHeader" />
  <main class="min-h-screen overflow-x-hidden" :class="[{ 'pb-80': withFooter }]">
    <slot />
  </main>
  <Footer v-if="withFooter" />
</template>

<script setup>
import Footer from '@/sections/Footer.vue'
import Header from '@/sections/Header.vue'

defineProps({
  withFooter: { type: Boolean, default: true },
  withHeader: { type: Boolean, default: true },
})
</script>
