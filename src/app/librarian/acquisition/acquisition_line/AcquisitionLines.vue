<template>
  <div class="size-full flex flex-col justify-center text-center" v-if="!currentAcq">Acquisition not found.</div>
  <div class="size-full flex flex-col overflow-hidden" v-else>
    <div class="flex items-start bg-background p-5 gap-2 border-b border-border">
      <Button variant="text" @click="router.back()" icon="arrow-left" />
      <div class="">
        <H6 class="flex items-center gap-3">
          Acquisiton ID#: {{ currentAcq.reference }}
          <span class="font-semibold bg-primary-soft text-primary rounded-lg uppercase tracking-wider text-xs p-2">{{ currentAcq?.acquisition_mode }}</span>
        </H6>
        <p>Date of Acquisition: {{ parse.formatDate(currentAcq?.acquisition_date) }}</p>
      </div>
      <div class="flex items-center ml-auto gap-2">
        <SearchItem @update:Lines="loadAcquisitionLines" />
        <Button variant="primary" as="link" :to="{ name: 'librarian.collections.catalog.new', query: { acquisition: true, acquisition_id: currentAcq.id } }">New Item</Button>
      </div>
    </div>

    <div class="flex-1 flex overflow-y-auto p-5">
      <Table title="Acquired Items" :subtitle="`Here are the items that is acquired from ${currentAcq?.dealer} through ${currentAcq?.acquisition_mode}.`" :data="currentLine">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left">Title</Th>
            <Th>Quantity</Th>
            <Th v-if="acquisition.currentData?.acquisition_mode === 'purchase'">Unit Price</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="lines.data" :loading="lines.loading" :cols="4">
          <Tr class="hover" v-for="(item, index) in lines.data">
            <Td :data="index + 1" />
            <Td class="text-left">
              <p class="font-medium">{{ item.item.title }}</p>
              <p class="text-xs text-muted-foreground">{{ item.item.subtitle }}</p>
            </Td>
            <Td :data="item.quantity" />
            <Td v-if="acquisition.currentData?.acquisition_mode === 'purchase'" :data="parse.toMoney(item.unitPrice)" />
            <Td class="space-x-2">
              <EditItemAcquisition :data="item" />
              <Button class="hover:text-danger" size="sm" @click="del(item)">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import EditItemAcquisition from '@/app/librarian/acquisition/acquisition_line/modals/EditItemAcquisition.vue'
import SearchItem from '@/app/librarian/acquisition/acquisition_line/modals/SearchItem.vue'
import type { Pagination } from '@/services/pagination'

const parse = useParser()
const route = useRoute()
const pop = usePopup()

const lines = useAcquisitionLinesStore()
const acquisition = useAcquisitionStore()
const breadcrumb = useBreadcrumbStore()

const currentAcq = ref<Acquisition | null>(null)
const currentLine = ref<Pagination>()

async function loadAcquisitionLines() {
  const resLine = await lines.fetch(route.params.id)
  currentLine.value = resLine.data
}

async function del(l: AcquisitionLine) {
  console.log(l)

  const confirm = await pop.confirm({ text: `Are you sure you want to delete "${l.item?.title}" from this acquisition?` })

  if (confirm.isConfirmed) {
    pop.info("Oops, this feature isn't up yet. Please come back later.")
  }
}

onBeforeMount(async () => {
  pop.load()
  try {
    const res = await acquisition.show(route.params.id)
    currentAcq.value = res.data
  } finally {
    loadAcquisitionLines()
    pop.unload()

    const key = `${route.name as string}:${route.params.id}`
    breadcrumb.set(key, `Viewing Acquisition: ${currentAcq.value?.reference}`)
  }
})
</script>

<style scoped></style>
