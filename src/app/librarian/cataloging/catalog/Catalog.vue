<template>
  <div class="flex flex-col size-full">
    <div class="flex items-center justify-between p-5">
      <div class="">
        <h5 class="text-2xl font-bold">Item Catalog</h5>
        <p>Browse the list of items in the library.</p>
      </div>
      <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.cataloging.add-new' }"> Add New Item </Button>
    </div>

    <div class="p-5 pt-0">
      <div class="flex items-center justify-between gap-5 bg-background p-5 rounded-2xl border border-border">
        <div class="flex items-center gap-3">
          <Control>
            <Label id="catalog-query">Search</Label>
            <Input id="catalog-query" v-model="search" placeholder="Search for an item in the catalog..." class="min-w-100" enable-clear />
          </Control>
          <CatalogFilter @filterApplied="filter" />
        </div>

        <p class="text-sm text-foreground-secondary">{{ items.length }} records</p>
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
        <tr v-for="(item, index) in items" :key="item.id">
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

watchDebounced(search, fetchCatalog, {
  deep: true,
  immediate: true,
  debounce: 500,
})
</script>
