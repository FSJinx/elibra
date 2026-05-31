<template>
  <div class="flex justify-center align-start w-screen h-screen overflow-hidden bg-gray-50" v-if="isMobile">
    <div class="flex flex-col items-center justify-center text-center h-max mt-[50%] gap-4 overflow-hidden">
      <img :src="images.no_no" alt="" class="h-50 w-auto" />
      <h1 class="font-bold text-3xl">Oh No!</h1>
      <p class="px-5">Seems like you're trying to access a page that's intended only for desktop.</p>
    </div>
  </div>
  <div class="flex w-screen h-screen overflow-hidden" v-else>
    <SidebarView />
  </div>
</template>

<script setup>
import images from '@/assets/images'
import { isMobileDevice } from '@/utilities/mobileDetector'
import SidebarView from '@/views/admin/SidebarView.vue'
import { computed } from 'vue'

const isMobile = computed(() => isMobileDevice())
</script>

<style scoped></style>
