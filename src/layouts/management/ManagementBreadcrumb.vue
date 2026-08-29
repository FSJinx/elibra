<template>
  <nav aria-label="Breadcrumb" class="flex items-center w-full bg-slate-100 px-4 py-2 text-sm border-b border-border">
    <ol class="inline-flex items-center gap-2 flex-wrap">
      <li v-for="(crumb, index) in breadcrumbs" :key="crumb.name || crumb.path || index" class="inline-flex items-center gap-2">
        <!-- Clickable Link (Parent Routes) -->
        <router-link v-if="index < breadcrumbs.length - 1" :to="crumb.path || { name: crumb.name ,params: {id: route.params.id} }" class="text-slate-600 hover:text-slate-900 hover:underline transition-colors font-medium">
          {{ crumb.meta.breadcrumb }}
        </router-link>

        <!-- Current Active Page (Non-clickable) -->
        <span v-else class="text-primary font-semibold" aria-current="page">
          {{ crumb.meta.breadcrumb }}
        </span>

        <!-- Separator Chevron -->
        <ChevronRight v-if="index < breadcrumbs.length - 1" :size="14" class="text-slate-400 shrink-0" />
      </li>
    </ol>
  </nav>
</template>

<script setup lang="ts">
import { ChevronRight } from '@lucide/vue'

const route = useRoute()
const { getBreadcrumb } = useBreadcrumb()

// Filter matched routes that have 'breadcrumb' defined in route meta
const breadcrumbs = computed(() => {
  return route.matched
    .filter((r) => r.meta.breadcrumb)
    .map((record) => ({
      ...record,
      meta: {
        ...record.meta,
        breadcrumb: getBreadcrumb(String(record.name), String(record.meta.breadcrumb)),
      },
    }))
})
</script>
