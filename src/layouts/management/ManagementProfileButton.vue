<template>
  <div ref="managementProfile" class="relative">
    <!-- Profile Trigger -->
    <Button type="button" class="group" :aria-expanded="open" aria-haspopup="menu" @click="toggle">
      <!-- Avatar -->
      <!-- <span class="flex size-8 shrink-0 items-center justify-center rounded-full border border-primary/15 bg-primary-soft text-xs font-bold text-primary">
        {{ user.getInitials }}
      </span> -->

      <!-- User Information -->
      <span class="hidden min-w-0 flex-col items-start sm:flex ml-1 mr-2">
        <span class="max-w-32 truncate text-sm font-semibold capitalize text-muted-foreground group-aria-expanded:text-foreground">
          {{ user.user?.first_name }}
        </span>
      </span>

      <ChevronDown :size="16" :stroke-width="2" class="shrink-0 text-foreground-secondary transition-transform duration-200 group-aria-expanded:rotate-180" />
    </Button>

    <!-- Dropdown -->
    <Transition name="pop-in">
      <div v-if="open" class="absolute right-0 z-50 mt-3 w-80 overflow-hidden rounded-xl border border-border bg-background shadow-xl ring-3 ring-inverse/3" role="menu">
        <!-- Account Header -->
        <div class="border-b border-border/70 p-4">
          <div class="flex items-center gap-3">
            <!-- Avatar -->
            <span class="flex size-12 shrink-0 items-center justify-center rounded-full border border-primary/15 bg-primary-soft text-base font-bold text-primary">
              {{ user.getInitials }}
            </span>

            <!-- Account Details -->
            <div class="min-w-0 flex-1">
              <div class="flex items-center gap-2">
                <h2 class="min-w-0 truncate text-sm font-semibold capitalize text-foreground">
                  {{ user.getFullName }}
                </h2>

                <Badge class="shrink-0 capitalize" :variant="parse.status(user.user?.role || 'patron')">
                  {{ user.user?.role }}
                </Badge>
              </div>

              <p class="mt-0.5 truncate text-xs text-foreground-secondary">@{{ user.user?.username }}</p>
            </div>
          </div>
        </div>

        <!-- Navigation -->
        <div class="p-2">
          <p class="px-3 pb-2 pt-1 text-[11px] font-semibold uppercase tracking-widest text-foreground-secondary">General</p>

          <div class="flex flex-col gap-0.5">
            <template v-for="route in routes">
              <Button as="link" variant="text" align="left" :icon="route.icon" class="optBtn px-5! gap-5 hover:bg-default cursor-pointer" :to="{ name: route.path }" @click="close"> {{ route.name }} </Button>
            </template>
          </div>
        </div>

        <!-- Logout -->
        <div class="border-t border-border/70 p-2">
          <Button variant="text" align="left" left-icon="door-open" class="optBtn px-5! gap-5 text-danger-soft-foreground hover:border-danger/10 hover:bg-danger/5 hover:text-danger focus:bg-danger-soft" @click="auth.logout"> Logout </Button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
import { ChevronDown } from '@lucide/vue'

const user = authStore()
const auth = useAuth()
const parse = useParser()

const open = ref(false)
const managementProfile = ref<HTMLElement | null>(null)

const routes = [
  { name: 'Home', path: '', icon: 'house' },
  { name: 'OPAC', path: '', icon: 'journals' },
  { name: 'Profile', path: '', icon: 'person' },
]

const toggle = () => {
  open.value = !open.value
}

const close = () => {
  open.value = false
}

useClickOutside(managementProfile, close)
</script>

<style scoped>
.optBtn {
  width: 100%;
  padding-inline: 0.75rem;
}
</style>
