<template>
  <header class="absolute top-0 flex items-center w-full p-5">
    <div class="grid grid-cols-5 w-6xl mx-auto">
      <div class="flex items-center col-span-4">
        <img :src="images.isu" alt="" class="" height="40" width="40" />
        <div class="ml-3">
          <h1 class="font-extrabold text-primary text-xl">e-Libra</h1>
          <p class="text-xs mb-0.5">The ISU-1 Centralized Library Management System for the <span class="font-bold text-primary uppercase">Isabela State university</span></p>
        </div>
      </div>
      <div class="flex items-center justify-end gap-2">
        <template v-if="auth.isAuthenticated">
          <button class="text-sm px-3 py-1.5 hover:underline rounded cursor-pointer" @click="auth.home">Dashboard</button>
          <img :src="images.isu" alt="" class="" height="30" width="30" />
        </template>
        <router-link :to="{ name: 'Login' }" class="px-4 py-1.5 border border-primary text-primary hover:bg-secondary/5 rounded cursor-pointer transition duration-200" v-else>Login</router-link>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import images from '@/assets/images'
import { authStore } from '../stores/auth'

const auth = authStore()
</script>

<style scoped></style>
