<template>
  <!-- Header -->
  <header class="grid grid-cols-3 border-slate-300">
    <!-- Banner -->
    <div class="flex flex-1 items-center py-4 px-5 gap-3 select-none mr-auto">
      <div class="flex rounded-sm p-2.5 border border-slate-300 bg-white cursor-pointer" @click="$emit('toggleSidebar')">
        <i class="fi fi-br-menu-burger leading-0 text-md"></i>
      </div>
      <div class="flex items-center gap-2">
        <img :src="images.isu" alt="" class="" height="30" width="30" />
        <div class="leading-normal">
          <h1 class="font-bold text-primary line-clamp-1">Isabela State University - Echague Campus</h1>
          <p class="text-xs">
            <span class="capitalize">{{ user?.role }}, University Library</span>
          </p>
        </div>
      </div>
    </div>

    <div class="flex items-center justify-center">
      <Search v-model="searchQuery" />
    </div>

    <div class="flex items-center justify-end px-5 gap-1">
      <Notification />
      <Profile />
    </div>
  </header>
</template>

<script setup lang="ts">
import images from '@/assets/images'
import { Bell } from '@lucide/vue'
import { ref } from 'vue'
import Notification from './notification/Notification.vue'
import Search from './search/Search.vue'
import { authStore } from '../../stores/authStore.js'
import Profile from './profile/Profile.vue'

const auth = authStore()
const user = auth?.user
const searchQuery = ref('')
</script>

<style scoped></style>
