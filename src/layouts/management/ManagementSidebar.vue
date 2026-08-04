<template>
  <aside class="shrink-0 flex flex-col h-full bg-background rounded-2xl hover:shadow-lg z-10 border overflow-hidden transition-all duration-300 ease-in-out" :class="[system.sidebar ? 'w-75 mr-5 border-border' : 'w-0 border-transparent bg-transparent']">
    <!-- Inner Fixed-Width Container prevents inner layout distortion -->
    <div class="w-75 flex flex-col h-full shrink-0 whitespace-nowrap">
      <!-- Header Logo -->
      <div class="flex flex-col gap-5 border-b border-border/50 p-5 shrink-0">
        <div class="flex items-center gap-3">
          <div class="size-10 rounded-xl bg-primary flex items-center justify-center text-primary-foreground shrink-0">
            <Logo class="text-xs" />
          </div>
          <div>
            <h1 class="text-lg font-bold tracking-tight text-primary">e-Libra</h1>
            <p class="text-xs text-foreground-secondary font-medium">Isabela State University ILMS</p>
          </div>
        </div>
        <!-- <ManagementSearch /> -->
      </div>

      <!-- Sidebar Menu -->
      <nav class="flex-1 overflow-y-auto scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/20 transition-all duration-200">
        <div class="p-3 pb-0 space-y-1" v-for="menu in filteredMenus" :key="menu.name">
          <h6 class="font-medium tracking-normal text-foreground-secondary text-sm">{{ menu.name }}</h6>
          <!-- Menu Children -->
          <div class="p-2 space-y-1">
            <router-link v-for="child in menu.children" :key="child.path" :to="{ name: child.path }" class="relative flex items-center gap-3.5 px-5 py-3 rounded-xl border cursor-pointer transition-all duration-200" :class="[active(child.path) ? 'text-primary bg-primary-soft hover:bg-primary-soft border-primary/25' : 'border-transparent hover:bg-default/50']" :data-title="active(child.path) ? child.name + ' (Selected)' : child.name">
              <div class="absolute left-0 bg-primary h-[50%] rounded-r-full transition-all duration-200" :class="[active(child.path) ? 'w-[0.30rem]' : 'w-0']"></div>
              <Icon :icon="child.icon" />
              <span>{{ child.name }}</span>
              <div class="absolute right-5 bg-primary h-2.5 rounded-full transition-all duration-200" :class="[active(child.path) ? 'w-2.5' : 'w-0']"></div>
            </router-link>
          </div>
        </div>
      </nav>

      <!-- Profile Icon at the Bottom -->
      <div class="flex items-center overflow-hidden gap-3 border border-border bg-default/10 rounded-2xl p-4 py-3 m-3 hover:border-primary cursor-pointer transition-all duration-200">
        <!-- Avatar -->
        <img :src="images.user" alt="" class="size-6 rounded-full shrink-0" />

        <!-- User Info (Dinagdagan ng min-w-0 para hindi itulak ang icon) -->
        <div class="flex flex-col text-sm tracking-tight min-w-0 flex-1">
          <h1 class="font-semibold line-clamp-1 truncate">{{ auth.fullName }}</h1>
          <p class="text-muted text-xs truncate">
            <span class="capitalize">{{ auth.user?.role }}</span> - @{{ auth.user?.username }}
          </p>
        </div>

        <!-- Icon Container -->
        <span class="ml-auto shrink-0 flex items-center justify-center">
          <Icon icon="ChevronUp" class="size-4" />
        </span>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import { menus } from '@/layouts/management/sidebar'

const route = useRoute()
const auth = authStore()
const system = systemStore()
const user = auth?.user

const filteredMenus = computed(() => {
  let menu = menus[user?.role as keyof typeof menus] ?? []
  return menu
})

function active(path: string) {
  return route.name === path
}
</script>
