<template>
  <div class="flex flex-col size-full overflow-hidden">
    <SectionHeader title="Catalog" description="Browse all item found in your catalog" icon="journals">
      <div class="flex items-center justify-end gap-2">
        <Button left-icon="plus-lg" variant="primary" as="link" :to="{ name: 'librarian.collections.catalog.new' }"> New Item </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <Card>
        <Form @submit="search()" class="flex items-center gap-2">
          <div class="flex-1 flex items-center gap-2">
            <Input id="catalog-query" v-model="params.query" placeholder="Search for an item in the catalog by title..." class="max-w-100" enable-clear />
            <Button type="submit" icon="search">Search</Button>
          </div>
          <CatalogFilter :params="params" @filterApplied="apply" />
          <Button @click="reset">Reset</Button>
        </Form>
      </Card>

      <Table :data-length="data?.length" title="Catalog List" subtitle="List of all library materials." :data="pagination" @per_page="apply({ per_page: $event })" @next="apply({ page: $event })" @prev="apply({ page: $event })">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Cover</Th>
            <Th class="text-left max-w-10">Title</Th>
            <Th>Item Type</Th>
            <Th>Call Number</Th>
            <Th>Released to OPAC</Th>
            <Th>Date Added</Th>
            <Th>Last Modified</Th>
          </tr>
        </Thead>

        <Tbody :loading="loading" :data="data">
          <Tr v-for="(item, index) in data" :key="item.id" :to="{ name: 'librarian.collections.catalog.view', params: { id: item.id } }">
            <Td :data="(index as number) + 1" />

            <Td>
              <img
                :src="coverUrl(item.cover_media?.file_path)"
                :alt="`${item.title} cover`"
                class="h-16 w-12 rounded border border-border object-cover"
              />
            </Td>

            <Td class="text-left">
              <Title :level="4">{{ item.title }}</Title>

              <p class="text-foreground-secondary line-clamp-1 max-w-150">
                {{ item.subtitle ?? item.description ?? 'This item has no subtitle or description.' }}
              </p>
            </Td>

            <Td :data="item_type(item.item_type_id)?.name" />
            <Td :data="item.call_number" />

            <Td>
              <Status :variant="String(item.released)">{{ item.released ? 'True' : 'False' }}</Status>
            </Td>

            <Td :data="parse.formatTimeAgo(item.created_at)" />
            <Td :data="parse.formatTimeAgo(item.updated_at)" />
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import CatalogFilter from '@/app/librarian/collections/catalog/modals/CatalogFilter.vue'
import book from '@/assets/images/default_book.png'
import { backendRoute } from '@/plugins/axios'

const items = useItemStore()
const options = optionStore()
const parse = useParser()

const defaultParams = (): Readonly<ItemParams> => ({
  query: '',
  order: 'desc',
  sort: '',
  page: '',
  per_page: '25',

  item_type_id: '',
  item_type_category_id: '',
})

const data = ref<Item[] | null>(null)
const loading = ref<boolean>(false)
const params = reactive<ItemParams>(defaultParams())
const pagination = ref<Pagination>()

const item_type = computed(() => (id: any) => options.item_types?.find((i) => i.id == id))
const coverUrl = (path?: string | null) =>
  path ? `${backendRoute.replace(/\/api\/?$/, '')}/storage/${path}` : book

function apply(p: Partial<ItemParams>) {
  Object.assign(params, p)
  search()
}

function reset() {
  Object.assign(params, defaultParams())
  search()
}

async function search() {
  loading.value = true
  try {
    const res = await items.fetch(params)
    pagination.value = res.data
    data.value = res.data.data
  } finally {
    loading.value = false
  }
}

watchDebounced(() => params.query, search, { debounce: 300 })
onBeforeMount(search)
</script>
