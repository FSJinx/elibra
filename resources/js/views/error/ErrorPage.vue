<template>
  <div class="flex flex-col min-h-screen relative">
    <Header />
    <section class="flex flex-1 justify-center items-center">
      <router-view />
    </section>
    <Footer />
  </div>
</template>

<script setup>
import Header from '@/components/public/HeaderNav.vue'
import LandingBody from '@/components/public/LandingBody.vue'
import Footer from '@/components/public/FooterNav.vue'
</script>
