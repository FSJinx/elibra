<template>
  <div class="size-full flex flex-col gap-3">
    <div class="action-buttons flex justify-end gap-2">
      <AddNewCopy @refresh="search" />
    </div>

    <Table title="Accessions" subtitle="List of all the copies of this item in this library" :data="pagination" @page="params.page = $event" @per_page="params.per_page = $event">
      <Thead>
        <tr>
          <Th class="max-w-50">No</Th>
          <Th class="text-left">Accession Number</Th>
          <Th>Shelf Location</Th>
          <Th>Date Added</Th>
          <Th>Status</Th>
          <Th>Actions</Th>
        </tr>
      </Thead>
      <Tbody :data="accessions.data" :loading="loading" cols="4">
        <Tr v-for="(item, index) in accessions.data" :key="index">
          <Td>{{ index + 1 }}</Td>
          <Td class="text-left" :data="item.accession_number" />
          <Td :data="item.section_id ? section(item.section_id)?.name : null" />
          <Td :data="parse.formatDateAgo(item.created_at)" />
          <Td>
            <Status :variant="item.status">{{ item.status }}</Status>
          </Td>
          <Td>
            <EditAccession :data="item" @refresh="search" />
          </Td>
        </Tr>
      </Tbody>
      <template #footer />
    </Table>
  </div>
</template>

<script setup lang="ts">
import AddNewCopy from '@/app/librarian/collections/catalog/modals/AddNewCopy.vue'
import EditAccession from '@/app/librarian/collections/catalog/modals/EditAccession.vue'

const props = defineProps<{ data: Item }>()

const { fetchResource } = itemStore()
const options = optionStore()
const parse = useParser()
const accessions = accessionStore()
const item = itemStore()

// const accessions = ref<Accession[] | null>()
const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const params = ref<Partial<AccessionParams>>({
  item_id: item?.currentData?.id ?? null,
})

const section = computed(() => (id: any) => options.sections?.find((i) => i.id == id))

async function search() {
  loading.value = true
  try {
    const res = await accessions.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}
onBeforeMount(search)
</script>

<style scoped></style>
