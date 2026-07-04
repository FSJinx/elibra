<template>
  <div class="sidebar h-full overflow-y-auto whitespace-nowrap text-nowrap flex-nowrap">
    <div class="" v-for="(menu, index) in menus.admin" :key="index">
      <div class="flex flex-col p-3 gap-2">
        <strong class="text-sm text-gray-500">{{ menu.name }}</strong>

        <router-link to="" class="flex items-center p-3 px-3 gap-2 rounded-md hover:bg-gray-100" v-for="(item, index) in menu.children" :key="index">
          <component :is="item.icon" class="h-5 w-5" />
          <span>{{ item.name }}</span>
        </router-link>
      </div>

      <hr class="border-slate-200" v-if="index !== Object.keys(menus.admin)[Object.keys(menus.admin).length - 1]" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { Home } from '@lucide/vue'
import { menus } from './sidebar'
</script>

<style scoped>
.sidebar {
  scrollbar-width: none;
}
</style>
