<template>
  <div class="flex flex-col size-full overflow-hidden">
    <SectionHeader title="Catalog" description="Browse all item found in your catalog" icon="journals">
      <div class="flex items-center justify-end gap-2">
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.collections.catalog.new' }"> New Item </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <Card>
        <Form @submit="search()" class="grid grid-cols-2 gap-2">
          <div class="flex items-center gap-2">
            <Input id="catalog-query" v-model="items.params.query" placeholder="Search for an item in the catalog..." class="max-w-150" enable-clear />
            <Button type="submit" icon="search" variant="success">Search</Button>
            <CatalogFilter @filterApplied="filter" />
            <Button variant="restore" @click="reset()">Reset</Button>
          </div>
        </Form>
      </Card>

      <Table :data-length="items.data?.length" title="Catalog List" subtitle="List of all library materials.">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left max-w-10">Title</Th>
            <Th>Item Type</Th>
            <Th>Call Number</Th>
            <Th>Released to OPAC</Th>
          </tr>
        </Thead>

        <Tbody cols="5" :loading="loading" :data="items.data">
          <Tr v-for="(item, index) in items.data" :key="item.id" :to="{ name: 'librarian.collections.catalog.view', params: { id: item.id } }">
            <Td :data="(index as number) + 1" />

            <Td class="text-left">
              <Title :level="4">{{ item.title }}</Title>

              <p class="text-foreground-secondary line-clamp-1 max-w-150">
                {{ item.subtitle ?? item.description ?? 'This item has no subtitle or description.' }}
              </p>
            </Td>

            <Td :data="item_types.byId(item.item_type_id)?.name" />
            <Td :data="item.call_number" />

            <Td>
              <Status :variant="String(item.released)">{{ item.released ? 'True' : 'False' }}</Status>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import CatalogFilter from '@/app/librarian/collections/catalog/modals/CatalogFilter.vue'

const items = useItemStore()
const item_types = useItemTypeStore()

const loading = ref(false)

// const params = reactive<Partial<ItemParams>>(itemDefaultParams())

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

function filter(f: any) {
  Object.assign(catalog, {
    category: f.category,
    status: f.status,
    sort: f.sort,
    order: f.order,
    item_type: f.item_type,
  })
}

async function search() {
  loading.value = true
  try {
    await items.fetch()
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
  }
}

function reset() {
  Object.assign(items.params, itemDefaultParams())
  nextTick(() => search())
}

watchDebounced(
  () => items.params.query,
  () => search(),
  { debounce: 300 },
)

onBeforeMount(async () => await search())
</script>
