<template>
  <header class="header fixed top-0 inset-0 p-5 w-full h-max bg-white">
    <div class="flex items-center w-full sm:w-7xl mx-auto">
      <!-- Banner -->
      <div class="flex items-center col-span-4">
        <img :src="images.logo" alt="" class="" height="40" width="40" />
        <div class="ml-3">
          <h1 class="font-extrabold text-primary text-xl">e-Libra</h1>
          <p class="text-xs mb-0.5 hidden sm:block">The ISU-1 Centralized Library Management System for the <span class="font-bold text-primary uppercase">Isabela State university</span></p>
        </div>
      </div>

      <!-- Linkers -->
      <div class="flex flex-1 items-center justify-end gap-3 mx-auto p-3">
        <button class="btn border border-primary text-primary hover:bg-secondary/5 transition-all duration-150" v-if="!isAuthenticated">Login</button>
        <template v-for="q in quickLinks" v-else>
          <router-link :to="{ name: q?.path }" class="btn hover:text-secondary transition duration-200">
            <component :is="q?.icon" class="h-4 w-4" />
            <span :title="'Go to ' + q.label">{{ q.label }}</span>
          </router-link>
        </template>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import images from '@/assets/images'
import { authStore } from '@/stores/auth';
import { ref } from 'vue'

const auth = authStore()
const isAuthenticated = auth.isAuthenticated

const quickLinks = ref({
  home: { label: 'Home', icon: 'House', path: '' },
  profile: { label: 'Profile', icon: 'UserCircle', path: '' },
  bag: { label: 'Book Bag', icon: 'ShoppingBag', path: '' },
})
</script>
