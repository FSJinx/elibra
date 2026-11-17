<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader :title="`${inventory.currentData?.inventory_code} • ${inventory.currentData?.section?.name ?? 'Unshelved'} (${parse.toCapital(inventory.currentData?.status)})`" :description="`Started: ${parse.formatDate(inventory.currentData?.created_at)} by ${inventory.currentData?.librarian?.user?.first_name}`" icon="journal">
      <div class="flex items-center justify-end gap-2 text-sm">
        <p class="px-3 py-1 rounded-md cursor-pointer" @click="params.verified = false" :class="[params.verified === false && 'border border-primary/50 bg-background text-primary']">Pending</p>
        <p class="px-3 py-1 rounded-md cursor-pointer" @click="params.verified = true" :class="[params.verified === true && 'border border-primary/50 bg-background text-primary']">Verified</p>
        <p class="px-3 py-1 rounded-md cursor-pointer" @click="params.verified = null" :class="[params.verified === null && 'border border-primary/50 bg-background text-primary']">All</p>
      </div>
    </SectionHeader>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col gap-3 p-5 overflow-y-auto">
      <div class="grid sm:grid-cols-6 items-center gap-3">
        <StatCard :loading :label="item.label" :value="item.data" v-for="item in stats" :value-color="item.variant"></StatCard>
      </div>

      <!-- Items Table -->
      <Table title="Items" subtitle="Verify items in this inventory" :data="pagination" @page="params.page = $event" @per_page="params.per_page = $event">
        <template #header>
          <Form class="flex-1 flex items-center justify-end gap-2" id="search-item" @submit="search">
            <Input type="text" placeholder="Search by title" class="max-w-100" v-model="params.query" />
            <Button form="search-item" type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession</Th>
            <Th class="text-left">Title</Th>
            <Th>Item Type</Th>
            <Th>Status</Th>
            <Th>Location</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :loading :data="lines.data">
          <tr v-for="(item, index) in lines.data" :key="item.id" class="border-b border-border hover:bg-muted/30 transition-colors">
            <Td :data="pagination?.from + index" />
            <Td :data="item.accession?.accession_number ?? null" />
            <Td class="text-left" :data="item.accession?.item?.title" />
            <Td :data="item.accession?.item?.item_type?.name" />
            <Td>
              <Status :variant="item.current_status">{{ item.current_status }}</Status>
            </Td>
            <Td :data="item.current_section?.name ?? 'Not on the shelf'" />
            <Td>
              <p class="text-foreground-secondary" v-if="item.verified_at">Verified</p>
              <VerifyInventory :data="item" @refresh="search" v-else/>
            </Td>
          </tr>
        </Tbody>
        <template #footer />
      </Table>
    </main>
  </div>
</template>

<script setup lang="ts">
import VerifyInventory from '@/app/librarian/collections/inventory/VerifyInventory.vue'

const pop = usePopup()
const parse = useParser()
const inventory = inventoryStore()
const lines = inventoryLineStore()

const defaultParams = (): Partial<InventoryLineParams> => ({
  inventory_id: inventory?.currentData?.id ?? null,
  verified: false,
})
const params = reactive(defaultParams())
const loading = ref<boolean>(false)
const pagination = ref<Pagination>()

const stats = computed(() => {
  return [
    { label: 'Total', data: pagination.value?.stats.total },
    { label: 'Verified', data: pagination.value?.stats.verified, variant: 'text-success!' },
    { label: 'Pending', data: pagination.value?.stats.pending, variant: 'text-info' },
    { label: 'Missing', data: pagination.value?.stats.missing, variant: 'text-danger!' },
  ]
})

async function search() {
  if (loading.value) return

  loading.value = true
  try {
    const res = await lines.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watchDebounced(params, search, { deep: true, immediate: true, debounce: 300 })
onBeforeUnmount(() => (inventory.currentData = null))
</script>

<style scoped></style>
