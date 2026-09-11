<template>
  <nav aria-label="Breadcrumb" class="flex items-center w-full min-h-10 px-5 border-b border-border bg-slate-50">
    <ol class="flex items-center min-w-0 gap-1 text-sm">
      <li v-for="(crumb, index) in breadcrumbs" :key="crumb.name || crumb.path || index" class="flex items-center min-w-0">
        <!-- Separator -->
        <ChevronRight v-if="index > 0" :size="15" :stroke-width="1.75" class="mx-1.5 shrink-0 text-muted-foreground/50" />

        <!-- Parent -->
        <router-link v-if="index < breadcrumbs.length - 1" :to="{ name: crumb.name, params: route.params }" class="min-w-0 max-w-50 truncate rounded-md text-muted-foreground font-medium transition-colors hover:text-primary cursor-pointer">
          {{ crumb.label }}
        </router-link>

        <!-- Current Page -->
        <span v-else class="min-w-0 max-w-60 truncate rounded-md font-semibold text-primary" aria-current="page" :data-title="crumb.label">
          {{ crumb.label }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
import { ChevronRight } from '@lucide/vue'

const route = useRoute()
const breadcrumbStore = useBreadcrumbStore()
const { overrides } = storeToRefs(breadcrumbStore)

const breadcrumbs = computed(() => {
  return route.matched
    .map((record) => {
      const key = `${record.name as string}:${route.params.id}`
      const label = overrides.value[key] ?? record.meta.breadcrumb
      return { ...record, label }
    })
    .filter((crumb) => crumb.label)
})

const getCrumbRoute = (crumb: (typeof breadcrumbs.value)[number]) => {
  if (crumb.path) {
    return crumb.path
  }

  return {
    name: crumb.name,
    params: route.params,
  }
}
</script>
