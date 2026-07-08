<template>
  <div class="flex flex-col w-full h-screen overflow-hidden">
    <Header @toggleSidebar="toggleSidebar" />

    <!-- Main Body -->
    <div class="flex">
      <!-- Sidebar -->
      <Sidebar :is-open="isSidebarOpen" />

      <main class="flex flex-1 flex-col h-full w-full overflow-y-auto bg-linear-to-br from-teal-50/50 via-white to-green-50/50">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import images from '@/assets/images'
import Sidebar from '../sidebar/Sidebar.vue'
import Header from './Header.vue'

const isSidebarOpen = ref(localStorage.getItem('__sidebar') === 'true')

const toggleSidebar = () => {
  console.log('Clicked')

  isSidebarOpen.value = !isSidebarOpen.value
  localStorage.setItem('__sidebar', isSidebarOpen.value.toString())
}
</script>

<style scoped></style>
