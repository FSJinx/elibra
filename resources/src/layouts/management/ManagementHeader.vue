<template>
  <!-- Header -->
  <header class="flex items-center border-b border-slate-300">
    <!-- Banner -->
    <div class="flex items-center py-4 px-5 gap-3 select-none mr-auto">
      <div class="flex rounded-sm border border-slate-300 p-2.5 cursor-pointer" @click="$emit('toggleSidebar')">
        <i class="fi fi-br-menu-burger leading-0 text-md"></i>
      </div>
      <div class="flex items-center gap-2">
        <img :src="images.isu" alt="" class="" height="30" width="30" />
        <div class="flex-1 leading-normal">
          <h1 class="font-bold text-primary">Isabela State University - Echague Campus</h1>
          <p class="text-xs">University Library</p>
        </div>
      </div>
    </div>

    <form class="flex items-center justify-center w-2xl">
      <input type="text" class="input" placeholder="Search for a setting here..." />
    </form>

    <div class="flex items-center justify-end px-5 gap-3 mr-5">
      <Bell class="h-6 w-6" />
      <span class="h-6 w-6 rounded-full border border-primary">
        <img :src="images.user" alt="" class="" />
      </span>
    </div>
  </header>
</template>

<script setup lang="ts">
import images from '@/assets/images'
import { Bell } from '@lucide/vue'
</script>

<style scoped></style>
