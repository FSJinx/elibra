<template>
  <aside class="p-5">
    <div class="shrink-0 flex flex-col h-full w-75 bg-background rounded-2xl hover:shadow-lg border border-border z-0">
      <!-- Header Logo -->
      <div class="flex flex-col gap-5 border-b border-border/50 p-5">
        <div class="flex items-center gap-3">
          <div class="size-10 rounded-xl bg-primary flex items-center justify-center text-primary-foreground">
            <Logo class="text-xs" />
          </div>
          <div>
            <h1 class="text-lg font-bold tracking-tight text-primary">e-Libra</h1>
            <p class="text-xs text-muted font-medium">Isabela State University ILMS</p>
          </div>
        </div>

        <!-- <ManagementSearch /> -->
      </div>

      <!-- Sidebar Menu -->
      <nav class="flex-1 overflow-y-auto scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/20 transition-all duration-200">
        <div class="p-3 pb-0 space-y-1" v-for="menu in filteredMenus">
          <h6 class="font-medium tracking-normal text-muted text-sm">{{ menu.name }}</h6>
          <!-- Menu Children -->
          <div class="p-2 space-y-1">
            <router-link v-for="child in menu.children" :to="{ name: child.path }" class="relative flex items-center gap-3.5 px-5 py-3 rounded-xl border cursor-pointer transition-all duration-200" :class="[active(child.path) ? 'text-primary bg-primary-soft hover:bg-primary-soft border-primary/25' : 'border-transparent hover:bg-default/50']" :data-title="child.name">
              <div class="absolute left-0 bg-primary h-[50%] rounded-r-full transition-all duration-200" :class="[active(child.path) ? 'w-[0.30rem]' : 'w-0']"></div>
              <Icon :icon="child.icon" />
              <span>{{ child.name }}</span>
              <div class="absolute right-5 bg-primary h-2.5 rounded-full transition-all duration-200" :class="[active(child.path) ? 'w-2.5' : 'w-0']"></div>
            </router-link>
          </div>
        </div>
      </nav>

      <!-- Profile Icon at the Bottom -->
      <div class="flex items-center gap-3 border border-border bg-default/10 rounded-2xl p-4 py-3 m-3 hover:border-primary cursor-pointer transition-all duration-200">
        <img :src="images.user" alt="" class="size-6" />
        <div class="flex flex-col text-sm tracking-tight">
          <h1 class="font-semibold line-clamp-1">{{ auth.fullName }}</h1>
          <p class="text-muted">
            <span class="capitalize">{{ auth.user?.role }}</span> - @{{ auth.user?.username }}
          </p>
        </div>
        <Icon icon="ChevronUp" />
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import menus from '@/layouts/sidebar/sidebar'
import ManagementSearch from '@/layouts/management_new/ManagementSearch.vue'
const route = useRoute()
const auth = authStore()
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
