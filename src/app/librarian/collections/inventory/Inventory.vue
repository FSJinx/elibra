<template>
  <div class="flex flex-col size-full overflow-hidden">
    <!-- Header -->
    <SectionHeader title="Stock Verification" description="Browse and verify materials on-shelf" icon="bookshelf">
      <div class="flex items-center justify-end gap-2">
        <Button variant="info" icon="download" @click="exportReport"> Export </Button>
        <Button variant="primary" icon="check-check" :disabled="!hasPendingCounts" @click="verifyAll"> Verify all counted </Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-4 p-5 overflow-y-auto scroll">
      <!-- Stats -->
      <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <StatCard label="Total items" :value="stats.total" icon="package" variant="default" />
        <StatCard label="Verified" :value="stats.verified" icon="check-circle" variant="success" />
        <StatCard label="Discrepancies" :value="stats.discrepancies" icon="alert-triangle" variant="danger" />
        <StatCard label="Pending count" :value="stats.pending" icon="clock" variant="warning" />
      </section>

      <!-- Filters -->
      <div class="p-5 bg-background border border-border rounded-xl">
        <Form>
          <Control>
            <Input id="inventory-search" placeholder="Search by SKU or item name..." v-model="filters.search" enable-clear />

            <Select id="inventory-category" title="Category" v-model="filters.category">
              <Option value="">All categories</Option>
              <template v-for="cat in categories" :key="cat">
                <Option :value="cat">{{ cat }}</Option>
              </template>
            </Select>
            <Select id="inventory-status" title="Status" v-model="filters.status">
              <Option value="">All statuses</Option>
              <Option value="pending">Pending</Option>
              <Option value="match">Match</Option>
              <Option value="over">Overage</Option>
              <Option value="under">Shortage</Option>
            </Select>
            <Button variant="text" @click="resetFilters">Reset</Button>
          </Control>
        </Form>
      </div>

      <Table title="Shelf Stocks" subtitle="Verify library materials that are available on-shelf." :data-length="filteredItems.length">
        <Thead>
          <Th>SKU</Th>
          <Th class="text-left">Item</Th>
          <Th>Category</Th>
          <Th>System qty</Th>
          <Th>Counted</Th>
          <Th>Variance</Th>
          <Th>Status</Th>
          <Th>Action</Th>
        </Thead>

        <Tbody :cols="8" :loading="false" :data="filteredItems">
          <tr v-for="row in filteredItems" :key="row.id" class="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
            <Td>{{ row.sku }}</Td>
            <Td class="text-left font-medium"> {{ row.name }}</Td>

            <Td>{{ row.category }}</Td>

            <Td>{{ row.system_qty }}</Td>

            <Td>
              <input type="number" min="0" v-model.number="row.counted_qty" placeholder="—" class="w-20 text-right rounded-lg border border-border bg-background px-2 py-1 text-sm tabular-nums focus:outline-none focus:ring-2 focus:ring-primary/30" />
            </Td>

            <Td class="tabular-nums" :class="varianceClasses(row)">
              {{ formatVariance(row) }}
            </Td>

            <Td>
              <Status class="text-xs capitalize px-3 py-1 rounded-full border border-current/30" :variant="statusVariant(rowStatus(row))">
                {{ rowStatus(row) }}
              </Status>
            </Td>

            <Td>
              <Button size="sm" variant="info" :disabled="row.counted_qty === null || row.counted_qty === undefined" @click="verifyItem(row)"> Verify </Button>
            </Td>
          </tr>

          <tr v-if="filteredItems.length === 0">
            <Td colspan="8" class="px-5 py-10 text-center text-sm">No items match your filters.</Td>
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
interface InventoryItem {
  id: number
  sku: string
  name: string
  category: string
  system_qty: number
  counted_qty: number | null
  verified: boolean
}

// TODO: replace with real fetch (e.g. useInventory() composable)
const items = reactive<InventoryItem[]>([
  { id: 1, sku: 'BK-00231', name: 'Introduction to Algorithms', category: 'Reference', system_qty: 12, counted_qty: null, verified: false },
  { id: 2, sku: 'BK-00987', name: 'Clean Architecture', category: 'Technology', system_qty: 8, counted_qty: 8, verified: false },
  { id: 3, sku: 'BK-01102', name: 'Noli Me Tangere', category: 'Literature', system_qty: 20, counted_qty: 18, verified: false },
  { id: 4, sku: 'BK-01455', name: 'Philippine History', category: 'History', system_qty: 15, counted_qty: 17, verified: false },
  { id: 5, sku: 'BK-01599', name: 'Calculus Vol. 1', category: 'Reference', system_qty: 10, counted_qty: null, verified: false },
])

const categories = computed(() => [...new Set(items.map((i) => i.category))])

const filters = reactive({
  search: '',
  category: '',
  status: '',
})

function rowStatus(row: InventoryItem): 'pending' | 'match' | 'over' | 'under' {
  if (row.counted_qty === null || row.counted_qty === undefined) return 'pending'
  if (row.counted_qty === row.system_qty) return 'match'
  return row.counted_qty > row.system_qty ? 'over' : 'under'
}

function statusVariant(status: string) {
  const map: Record<string, string> = {
    pending: 'default',
    match: 'success',
    over: 'warning',
    under: 'danger',
  }
  return map[status] ?? 'default'
}

function varianceClasses(row: InventoryItem) {
  const status = rowStatus(row)
  if (status === 'pending') return 'text-foreground-secondary'
  if (status === 'match') return 'text-success'
  if (status === 'over') return 'text-warning'
  return 'text-danger'
}

function formatVariance(row: InventoryItem) {
  if (row.counted_qty === null || row.counted_qty === undefined) return '—'
  const diff = row.counted_qty - row.system_qty
  return diff > 0 ? `+${diff}` : `${diff}`
}

const filteredItems = computed(() =>
  items.filter((row) => {
    const matchesSearch = !filters.search || row.name.toLowerCase().includes(filters.search.toLowerCase()) || row.sku.toLowerCase().includes(filters.search.toLowerCase())

    const matchesCategory = !filters.category || row.category === filters.category
    const matchesStatus = !filters.status || rowStatus(row) === filters.status

    return matchesSearch && matchesCategory && matchesStatus
  }),
)

const stats = computed(() => ({
  total: items.length,
  verified: items.filter((i) => i.verified).length,
  discrepancies: items.filter((i) => rowStatus(i) === 'over' || rowStatus(i) === 'under').length,
  pending: items.filter((i) => rowStatus(i) === 'pending').length,
}))

const hasPendingCounts = computed(() => items.some((i) => i.counted_qty !== null && !i.verified))

function verifyItem(row: InventoryItem) {
  // TODO: call backend, e.g. await inventoryStore.verify(row.id, row.counted_qty)
  row.verified = true
}

function verifyAll() {
  items.forEach((row) => {
    if (row.counted_qty !== null && !row.verified) row.verified = true
  })
}

function resetFilters() {
  filters.search = ''
  filters.category = ''
  filters.status = ''
}

function exportReport() {
  // TODO: hook up to CSV/PDF export endpoint
}
</script>

<style scoped></style>
