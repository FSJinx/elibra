```vue
<template>
  <div class="sticky top-0 z-20 border-b border-border/70 bg-background shadow-sm">
    <!-- Back Navigation -->
    <div class="px-5 pt-3">
      <Button :to="{ name: 'librarian.cataloging.catalog' }" as="link" variant="text" class="gap-3" icon="arrow-left">
        <span>Back to catalog</span>
      </Button>
    </div>

    <!-- Detail Navigation -->
    <nav aria-label="Catalog item navigation" class="flex items-center px-5 gap-2">
      <router-link v-for="item in routes" :key="item.path" :to="{ name: item.path }" class="group relative flex justify-center px-3 py-3 text-sm transition-colors" :class="[active(item.path) ? 'text-primary' : 'font-medium text-muted-foreground hover:text-primary']">
        {{ item.name }}

        <!-- Active Indicator -->
        <span class="absolute bottom-0 h-0.75 rounded-full bg-primary transition-all duration-200" :class="active(item.path) ? 'w-full' : 'w-0 group-hover:w-full'" />
      </router-link>
    </nav>
  </div>

  <div class="w-full">
    <router-view />
  </div>
</template>

<script setup lang="ts">
const route = useRoute()

const routes = [
  { name: 'Overview', path: 'librarian.cataloging.catalog.view.overview' },
  { name: 'Authors', path: 'librarian.cataloging.catalog.view.authors' },
  { name: 'Accession', path: 'librarian.cataloging.catalog.view.accession' },
  { name: 'Acquisition History', path: 'librarian.cataloging.catalog.view.acquisition' },
]

const active = (path: string) => {
  return route.name === path
}
</script>
```
