<template>
  <Transition name="sidebar">
    <aside v-if="system.sidebar" class="max-w-85 w-full shrink-0 flex flex-col flex-1 bg-background border-r border-border overflow-hidden select-none" aria-label="Main Sidebar Navigation">
      <!-- Inner Fixed-Width Container -->
      <div class="w-85 flex flex-col h-full shrink-0 whitespace-nowrap">
        <!-- Header Logo -->
        <router-link :to="{ name: 'home' }" class="flex items-center gap-3 p-5 shrink-0 border-b border-border/50 hover:bg-default/30 transition-colors">
          <div class="size-10 rounded-lg bg-primary flex items-center justify-center text-primary-foreground shrink-0 shadow-sm">
            <Logo class="text-sm mb-0.5" />
          </div>
          <div class="min-w-0">
            <h1 class="text-lg font-bold tracking-tight text-primary leading-tight">e-Libra</h1>
            <p class="text-xs text-foreground-secondary font-medium truncate">Isabela State University ILMS</p>
          </div>
        </router-link>

        <!-- Navigation Menu -->
        <nav class="flex-1 overflow-y-auto p-3 space-y-4 scrollbar-thin scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/20 transition-all duration-200">
          <div v-for="menu in filteredMenus" :key="menu.name" class="space-y-1">
            <h2 class="px-3 text-xs font-semibold uppercase tracking-wider text-foreground-secondary/70">
              {{ menu.name }}
            </h2>

            <!-- Menu Children -->
            <div class="space-y-1 pt-1">
              <router-link
                v-for="child in childrenOf(menu.children)"
                :key="child.path"
                :to="{ name: child.path }"
                class="relative flex items-center gap-3.5 px-4 py-3 rounded-xl border font-medium cursor-pointer transition-all duration-200"
                :class="[isActive(child.path) ? 'text-primary bg-primary-soft/25 hover:bg-primary-soft border-primary/25 shadow-sm' : 'border-transparent hover:bg-slate-50 text-foreground-secondary hover:text-foreground']"
                :aria-current="isActive(child.path) ? 'page' : undefined"
              >
                <!-- Active Indicator Bar -->
                <div class="absolute left-0 bg-primary h-[50%] rounded-r-full transition-all duration-200" :class="[isActive(child.path) ? 'w-1.5' : 'w-0']" />

                <Icon :icon="child.icon" class="text-lg shrink-0" />
                <span class="truncate">{{ child.name }}</span>
              </router-link>
            </div>
          </div>
        </nav>

        <!-- Profile Footer Section -->
        <div class="flex items-center gap-3 p-5 border-t border-border bg-background/50 shrink-0">
          <!-- Avatar -->
          <div class="relative size-10 rounded-full shrink-0 border border-border overflow-hidden bg-background flex items-center justify-center">
            <img v-if="auth.user?.profile_photo" :src="auth.user.profile_photo" :alt="auth.getFullName" class="size-full object-cover" />
            <span v-else class="font-semibold text-primary text-xs m-auto">
              {{ auth.getInitials }}
            </span>
          </div>

          <!-- User Info -->
          <div class="flex flex-col flex-1">
            <span class="font-semibold text-foreground truncate capitalize">
              {{ auth.getFullName || 'User' }}
            </span>
            <span class="text-foreground-secondary truncate text-xs">
              {{ parse.toCapital(auth.user?.role || 'Guest') }}
              <template v-if="auth.user?.username">• @{{ auth.user.username }}</template>
            </span>
          </div>
        </div>
      </div>
    </aside>
  </Transition>
</template>

<script setup lang="ts">
import { menus } from '@/layouts/management/sidebar'

const route = useRoute()
const auth = authStore()
const system = systemStore()
const parse = useParser()

// Filtered Menu base sa Role (Reactivity preserved)
const filteredMenus = computed(() => {
  const role = auth.user?.role as keyof typeof menus
  return menus[role] ?? []
})

function childrenOf(children: Record<string, any> | any[]) {
  return Array.isArray(children) ? children : Object.values(children)
}

// Route Active Checker
function isActive(path: string): boolean {
  const currentName = String(route.name || '')
  return currentName === path || currentName.startsWith(`${path}.`)
}
</script>

<style scoped>
.sidebar-enter-active,
.sidebar-leave-active {
  transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
  overflow: hidden;
}

.sidebar-enter-from,
.sidebar-leave-to {
  width: 0;
  opacity: 0;
  margin-left: -21.25rem;
}
</style>
