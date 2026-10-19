<template>
  <!-- Loading -->
  <div class="size-full flex flex-col justify-center items-center" v-if="loading">
    <Spinner class="text-xl mb-5" />
    <p>Loading, please wait...</p>
  </div>

  <!-- No Data -->
  <div class="size-full flex flex-col justify-center items-center" v-else-if="!data">
    <Spinner />
  </div>

  <div class="size-full flex flex-col overflow-hidden" v-else>
    <div class="border-b border-border/70 bg-background shadow-sm">
      <!-- Back Navigation -->
      <div class="px-5 pt-3">
        <Button :to="{ name: 'librarian.collections.catalog' }" as="link" variant="text" class="gap-3" icon="arrow-left">
          <span>{{ data.title }}</span>
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

    <div class="flex-1 p-5 overflow-y-auto">
      <router-view :data="data" />
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const item = useItemStore()
const pop = usePopup()

const loading = ref<boolean>(false)
const data = ref<Item | null>(null)

const routes = [
  { name: 'Overview', path: 'librarian.collections.catalog.view.overview' },
  { name: 'Authors', path: 'librarian.collections.catalog.view.authors' },
  { name: 'Accession', path: 'librarian.collections.catalog.view.accession' },
  { name: 'Acquisition', path: 'librarian.collections.catalog.view.acquisition' },
]

const active = (path: string) => {
  return route.name === path
}

onBeforeMount(async () => {
  loading.value = true
  try {
    const res = await item.show(route.params.id)
    data.value = res.data
  } finally {
    loading.value = false
  }
})
</script>
