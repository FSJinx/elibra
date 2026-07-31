<template>
  <div class="flex h-screen w-full bg-linear-to-b from-background to-primary-soft/50 overflow-hidden">
    <!-- Sidebar -->
    <div class="p-5">
      <aside class="shrink-0 flex flex-col h-full w-75 bg-background rounded-2xl border border-border overflow-hidden">
        <!-- Header Logo -->
        <div class="flex items-center gap-3 p-5 border-b border-border/50">
          <div class="size-10 rounded-lg bg-primary flex items-center justify-center text-primary-foreground">
            <Logo width="18" />
          </div>
          <div>
            <h1 class="text-xl font-bold tracking-tight text-primary">e-Libra</h1>
            <p class="text-xs text-muted font-medium">Isabela State University ILMS</p>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="flex-1 overflow-auto scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/20 transition-all duration-200">
          <div class="p-3" v-for="menu in filteredMenus">
            <h6 class="uppercase font-semibold tracking-wide text-muted text-sm mb-2">{{ menu.name }}</h6>
            <!-- Menu Children -->
            <div class="space-y-1" v-for="child in menu.children">
              <router-link :to="{ name: child.path }" class="relative flex items-center gap-3 px-5 py-3 rounded-xl cursor-pointer transition-all duration-200" :class="[active(child.path) ? 'text-primary bg-primary-soft hover:bg-primary-soft' : 'hover:bg-default/50']">
                <div class="absolute left-0 bg-primary h-[50%] rounded-r-full transition-all duration-200" :class="[active(child.path) ? 'w-[0.30rem]' : 'w-0']"></div>
                <Icon :icon="child.icon" />
                <span>{{ child.name }}</span>
              </router-link>
            </div>
          </div>
        </nav>

        <div class="flex items-center gap-3 border border-border bg-default/10 rounded-2xl p-4 py-3 m-3 hover:shadow-md cursor-pointer transition-all duration-200">
          <img :src="images.user" alt="" class="size-6" />
          <div class="flex flex-col text-sm tracking-tight">
            <h1 class="font-semibold line-clamp-1">{{ auth.fullName }}</h1>
            <p class="capitalize">{{ auth.user?.role }}</p>
          </div>
          <Icon icon="ChevronUp" />
        </div>
      </aside>
    </div>

    <main class="flex-1 overflow-auto p-5 font-poppins scrollbar-thumb-transparent hover:scrollbar-thumb-background-inverse/25 transition-all duration-200">
      <div class="flex justify-between items-end">
        <div class="space-y-1 my-5">
          <p class="text-primary uppercase tracking-wide text-sm font-semibold">{{ route.meta.title ?? 'Untitled' }}</p>
          <h1 class="text-2xl font-semibold">Good day, {{ auth.user?.first_name }}!👋</h1>
          <p class="text-md">This is your today's overview.</p>
        </div>

        <!-- <div class="border border-border rounded-xl p-4"></div> -->
      </div>

      <router-view />
    </main>
  </div>
</template>

<script setup lang="ts">
import { menus } from '@/layouts/sidebar/sidebar'
const route = useRoute()
const auth = authStore()
const system = systemStore()
const user = auth?.user

const filteredMenus = computed(() => {
  // let menu = user?.role && user.role in menus ? menus[user.role as keyof typeof menus] : []
  let menu = menus[user?.role as keyof typeof menus] ?? []

  return menu
})

function active(path: string) {
  return route.name === path
}
</script>

<style scoped></style>
