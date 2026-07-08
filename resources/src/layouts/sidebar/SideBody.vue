<template>
  <div class="sidebar h-full overflow-y-auto whitespace-nowrap text-nowrap flex-nowrap">
    <div class="" v-for="(menu, index) in menus.librarian" :key="index">
      <div class="flex flex-col p-3 gap-2">
        <p class="text-sm text-gray-500 font-semibold">{{ menu.name }}</p>

        <template v-for="(item, index) in menu.children" :key="index">
          <router-link :to="{ name: item.path }" class="flex items-center p-3 px-3 gap-2 rounded-md hover:bg-gray-100">
            <component :is="item.icon" class="h-5 w-5" />
            <span class="">{{ item.name }}</span>
          </router-link>
        </template>
      </div>

      <hr class="border-slate-200" v-if="index !== Object.keys(menus.admin)[Object.keys(menus.admin).length - 1]" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { Home } from '@lucide/vue'
import { menus } from './sidebar'
import { authStore } from '../../stores/auth'
import { computed, ref } from 'vue'

const auth = authStore()
const user = auth?.user

const filteredMenus = computed(() => {
  let menu = user?.role && user.role in menus ? menus[user.role as keyof typeof menus] : []

  
  return menu
})
</script>

<style scoped>
.sidebar {
  scrollbar-width: none;
}
</style>
