<template>
  <header class="flex justify-between items-center w-full h-[8vh] px-6 sticky top-0 bg-white shadow z-50">
    <a href="/" class="flex justify-center items-center gap-3">
      <img :src="images.logo" alt="Logo" width="auto" class="h-10" />
      <span class="font-bold font-sans text-primary text-lg">e-Libra</span>
    </a>

    <!-- Navs -->
    <div class="flex">
      <a href="#" class="nav-link">Home</a>
      <a href="#" class="nav-link">Features</a>
      <a href="#" class="nav-link">About</a>
      <a href="#" class="nav-link">Contact Us</a>
    </div>

    <!-- User Portal -->
    <div class="flex gap-2">
      <router-link :to="{ name: 'login' }" class="nav-btn border border-primary text-primary hover:bg-primary hover:text-white transition">Login</router-link>
      <a href="#" class="nav-btn bg-primary text-white">Register</a>
    </div>
  </header>
</template>

<script setup>
import images from '@/assets/images'

</script>
