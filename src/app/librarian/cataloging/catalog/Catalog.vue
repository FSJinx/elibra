<template>
  <div class="flex flex-col size-full bg-background">
    <!-- Title Page -->
    <div class="flex items-start justify-between p-5">
      <div class="space-y-px">
        <h5 class="text-3xl font-semibold">
          <Icon icon="journals" />
          Item Catalog
        </h5>
        <div class="space-x-2 text-muted-foreground">
          <span class="text-sm">Browse the list of items in the library.</span>
        </div>
      </div>
    </div>

    <!-- Statistical Cards -->
    <CatalogCards />

    <!-- Data Manipulators -->
    <div class="flex items-center justify-between gap-5 bg-background p-5">
      <p class="text-sm text-muted-foreground px-3 py-1 bg-muted rounded-full border border-border">{{ items.length }} records</p>

      <div class="flex items-center gap-2">
        <Control class="gap-5">
          <Label id="catalog-query">Search</Label>
          <Input id="catalog-query" v-model="search" placeholder="Search for an item in the catalog..." class="min-w-100" enable-clear />
        </Control>
        <CatalogFilter @filterApplied="filter" />
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.cataloging.add-new' }"> Add New Item </Button>
      </div>
    </div>

    <!-- Catalog Table -->
    <Table>
      <Thead>
        <tr>
          <Th>No</Th>
          <Th class="text-left">Title</Th>
          <Th>Call Number</Th>
          <Th>Publication Year</Th>
        </tr>
      </Thead>

      <Tbody :columns="4" :loading="loading" :data="items">
        <tr class="hover" v-for="(item, index) in items" :key="item.id" @click="view(item.id)">
          <Td :data="index + 1" />

          <Td class="text-left">
            <p class="text-lg font-medium">
              {{ item.title }}
            </p>

            <p v-if="item.subtitle" class="text-sm text-foreground-secondary">
              {{ item.subtitle }}
            </p>
          </Td>

          <Td :data="item.call_number" />

          <Td :data="item.publication_year" />
        </tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
import CatalogCards from '@/app/librarian/cataloging/catalog/CatalogCards.vue'
import CatalogFilter from '@/app/librarian/cataloging/catalog/CatalogFilter.vue'

interface CatalogItem {
  id: number
  title: string
  subtitle?: string | null
  call_number: string
  publication_year: number | null
}

const items = ref<CatalogItem[]>([])
const loading = ref(false)
const search = ref('')

const catalog = reactive<{
  category: string
  status: string
  sort: string | null
  order: 'asc' | 'desc'
  item_type: number | string | null
}>({
  category: '',
  status: '',
  sort: '',
  order: 'asc',
  item_type: '',
})

async function fetchCatalog() {
  loading.value = true

  try {
    const res = await api.get('item/get', {
      params: { search: search.value, ...catalog },
    })

    items.value = res.data?.data ?? []
  } catch (error) {
    console.error('Failed to fetch catalog:', error)
    items.value = []
  } finally {
    loading.value = false
  }
}

function filter(f: any) {
  Object.assign(catalog, {
    category: f.category,
    status: f.status,
    sort: f.sort,
    order: f.order,
    item_type: f.item_type,
  })

  fetchCatalog()
}

function view(id: number) {
  return router.push({ name: 'librarian.cataloging.catalog.view', params: { id: id } })
}

watchDebounced(search, fetchCatalog, {
  deep: true,
  immediate: true,
  debounce: 500,
})
</script>
