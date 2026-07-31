<template>
  <div class="flex flex-col w-full h-screen overflow-hidden bg-slate-50">
    <Header />

    <!-- Main Body -->
    <div class="flex flex-1 min-h-0">
      <!-- Sidebar -->
      <Sidebar />

      <main class="flex-1 w-full overflow-y-auto overflow-x-hidden border-l border-t border-slate-300 bg-white rounded-tl-2xl">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
import Sidebar from '../sidebar/Sidebar.vue'
import Header from './Header.vue'
</script>

<style scoped>
main {
  scrollbar-color: transparent transparent;
  transition: scrollbar-color 0.25s ease;
}

main:hover {
  scrollbar-color: #808080bf transparent;
}
</style>
