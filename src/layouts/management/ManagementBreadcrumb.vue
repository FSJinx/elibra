
<template>
  <nav aria-label="Breadcrumb" class="flex items-center w-full min-h-10 px-5 border-b border-border bg-slate-50">
    <ol class="flex items-center min-w-0 gap-1 text-sm">
      <li v-for="(crumb, index) in breadcrumbs" :key="crumb.name || crumb.path || index" class="flex items-center min-w-0">
        <!-- Separator -->
        <ChevronRight v-if="index > 0" :size="15" :stroke-width="1.75" class="mx-1.5 shrink-0 text-muted-foreground/50" />

        <!-- Parent -->
        <router-link v-if="index < breadcrumbs.length - 1" :to="getCrumbRoute(crumb)" class="min-w-0 max-w-50 truncate rounded-md text-muted-foreground font-medium transition-colors hover:text-primary cursor-pointer">
          {{ crumb.meta.breadcrumb }}
        </router-link>

        <!-- Current Page -->
        <span v-else class="min-w-0 max-w-60 truncate rounded-md font-semibold text-primary" aria-current="page">
          {{ crumb.meta.breadcrumb }}
        </span>
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
import { ChevronRight } from '@lucide/vue'

const route = useRoute()
const { getBreadcrumb } = useBreadcrumb()

const breadcrumbs = computed(() => {
  return route.matched
    .filter((record) => record.meta.breadcrumb)
    .map((record) => ({
      ...record,
      meta: {
        ...record.meta,
        breadcrumb: getBreadcrumb(String(record.name), String(record.meta.breadcrumb)),
      },
    }))
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
```
