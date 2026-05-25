<template>
  <div class="flex items-center justify-center w-full h-screen relative">
    <div class="flex w-250 h-150 relative rounded-lg overflow-hidden shadow-xl border border-gray-100">
      <!-- Left Panel -->
      <div class="flex flex-col w-[50%] h-full z-1 relative">
        <router-link :to="{ name: 'home' }" class="flex gap-2 items-center absolute top-0 left-0 m-8 z-2">
          <img :src="images.logo_white" alt="Isabela State University Logo" class="w-8 h-auto" />
          <span class="text-xl font-extrabold text-white">e-Libra</span>
        </router-link>

        <div class="h-full w-full flex flex-col items-center justify-center z-1">
          <img :src="images.isu" alt="Isabela State University Logo" class="h-auto w-35 mb-10" />
          <h1 class="sm:px-5 md:w-[90%] max-w-150 text-2xl text-center text-white font-bold">e-Libra: A Centralized Web-Based Library Management and Resource Monitoring System for the Isabela State University</h1>
        </div>

        <!-- Gate Gradient -->
        <div class="w-full h-full absolute top-0 left-0 z-0 bg-primary">
          <img :src="images.gate" alt="Isabela State University Gate" class="absolute top-0 left-0 w-full h-full object-cover object-center opacity-20 z-0" />
        </div>
      </div>

      <!-- Right Panel -->
      <div class="flex items-center justify-center w-[50%] h-full z-1 relative p-5">
        <LoginCard />
      </div>
    </div>
  </div>
</template>

<script setup>
import images from '@/assets/images'
import LoginCard from '@/components/auth/LoginCard.vue'
</script>
