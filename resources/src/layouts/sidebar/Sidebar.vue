<template>
  <aside :class="['flex flex-col h-full relative border-slate-300 whitespace-nowrap text-nowrap flex-nowrap overflow-hidden transition-all duration-300', isOpen ? 'w-70' : 'w-0']">
    <div class="relative flex flex-col min-w-70 overflow-hidden py-0">
      <div class="sidebar h-full whitespace-nowrap text-nowrap flex-nowrap pl-1 pt-0">
        <div class="" v-for="(menu, index) in filteredMenus" :key="index">
          <div class="flex flex-col p-2 gap-2">
            <p class="text-sm text-gray-500 font-semibold">{{ menu.name }}</p>

            <template v-for="(item, index) in menu.children" :key="index">
              <router-link :to="{ name: item.path }" class="flex items-center p-4 gap-2 rounded hover:bg-slate-200 transition duration-150" exact-active-class="bg-slate-200">
                <Eicon :icon="item.icon" />
                <span class="">{{ item.name }}</span>
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { menus } from './sidebar'
import { authStore } from '../../stores/auth'
import { computed, ref } from 'vue'
import Eicon from '../../components/ui/Eicon.vue'

const auth = authStore()
const user = auth?.user

const filteredMenus = computed(() => {
  // let menu = user?.role && user.role in menus ? menus[user.role as keyof typeof menus] : []
  let menu = menus[user?.role as keyof typeof menus] ?? []

  return menu
})

const props = withDefaults(
  defineProps<{
    isOpen?: boolean
  }>(),
  { isOpen: false },
)
</script>

<style scoped>
.sidebar {
  overflow-y: auto;
  scrollbar-width: 8px;
  scrollbar-color: transparent transparent;

  transition: scrollbar-color 0.5s ease;
}

.sidebar:hover {
  scrollbar-color: gray transparent;
}
</style>
