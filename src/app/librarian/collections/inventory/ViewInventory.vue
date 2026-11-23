<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader :title="inventory.currentData?.inventory_code ?? 'Inventory'" :description="`Captured ${parse.formatDate(inventory.currentData?.created_at)} by ${inventory.currentData?.librarian?.user?.name?.full ?? 'Unknown librarian'}`" icon="journal">
      <div class="flex items-center justify-end gap-2">
        <Button variant="primary" left-icon="printer" @click="download">Print PDF</Button>
      </div>
    </SectionHeader>

    <main class="flex-1 flex flex-col gap-3 p-5 overflow-y-auto">
      <div class="grid gap-3 sm:grid-cols-3">
        <StatCard :loading="loading" label="Inventory copies" :value="pagination?.total ?? 0" icon="collection" variant="primary" />
        <StatCard :loading="loading" label="Shelf locations" :value="shelfLocationCount" icon="building" variant="success" />
        <StatCard :loading="loading" label="Capture status" value="Completed" icon="check-circle" variant="success" />
      </div>

      <Table title="Inventory Copies" subtitle="Grouped by item type and title for shelf-to-shelf verification" :data="pagination" @page="params.page = $event" @per_page="params.per_page = $event">
        <template #header>
          <Form class="flex-1 flex items-center justify-end gap-2" @submit="search">
            <Input type="text" placeholder="Search accession or title" class="max-w-100" v-model="params.query" />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession Number</Th>
            <Th class="text-left">Title</Th>
            <Th>Item Type</Th>
            <Th>Status at Inventory</Th>
            <Th>Shelf Location</Th>
            <Th>Manual Check</Th>
          </tr>
        </Thead>
        <Tbody :loading="loading" :data="lines.data" error-message="No copies were found in this inventory.">
          <template v-for="group in groupedLines" :key="group.key">
            <tr class="border-y border-border bg-secondary/50">
              <td colspan="7" class="px-4 py-3">
                <div class="flex items-center justify-between gap-3">
                  <div class="space-x-3">
                    <span class="font-semibold uppercase tracking-wider text-primary">{{ group.itemType }}</span>
                   
                    <span class="mt-0.5 font-semibold"> • {{ group.title }}</span>
                  </div>
                  <span class="shrink-0 rounded-full bg-primary/10 px-2.5 py-1 text-xs font-semibold text-primary">Qty {{ group.lines.length }}</span>
                </div>
              </td>
            </tr>
            <Tr v-for="(item, index) in group.lines" :key="item.id">
              <Td :data="index + 1" />
              <Td :data="item.accession?.accession_number" />
              <Td class="text-left" :data="item.accession?.item?.title" />
              <Td :data="item.accession?.item?.item_type?.name" />
              <Td
                ><Status :variant="item.current_status">{{ item.current_status }}</Status></Td
              >
              <Td :data="item.current_section?.name ?? item.accession?.section?.name ?? 'Unshelved'" />
              <Td>
                <div class="flex justify-center gap-2 text-xs">
                  <span class="rounded-md border border-border px-2 py-1">Found □</span>
                  <span class="rounded-md border border-border px-2 py-1">Missing □</span>
                </div>
              </Td>
            </Tr>
          </template>
        </Tbody>
      </Table>
    </main>
  </div>
</template>

<script setup lang="ts">
const inventory = inventoryStore()
const lines = inventoryLineStore()
const parse = useParser()

const loading = ref(false)
const pagination = ref<Pagination>()
const params = reactive<Partial<InventoryLineParams>>({
  inventory_id: inventory.currentData?.id ?? null,
  query: '',
})

const shelfLocationCount = computed(() => {
  const locations = new Set((lines.data ?? []).map((line) => line.current_section?.name ?? line.accession?.section?.name ?? 'Unshelved').filter(Boolean))
  return locations.size
})

const groupedLines = computed(() => {
  const groups = new Map<string, { key: string; itemType: string; title: string; lines: InventoryLine[] }>()
  for (const line of lines.data ?? []) {
    const itemType = line.accession?.item?.item_type?.name ?? 'Unclassified'
    const title = line.accession?.item?.title ?? 'Untitled'
    const key = `${itemType}\u0000${title}`
    const group = groups.get(key)
    if (group) {
      group.lines.push(line)
    } else {
      groups.set(key, { key, itemType, title, lines: [line] })
    }
  }
  return [...groups.values()].sort((a, b) => a.itemType.localeCompare(b.itemType) || a.title.localeCompare(b.title))
})

async function search() {
  loading.value = true
  try {
    const res = await lines.fetch(params)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function download() {
  if (!inventory.currentData?.id) return
  const blob = await inventory.downloadPdf(inventory.currentData.id)
  const url = URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `${inventory.currentData.inventory_code}.pdf`
  link.click()
  URL.revokeObjectURL(url)
}

watchDebounced(params, search, { deep: true, immediate: true, debounce: 300 })
</script>
