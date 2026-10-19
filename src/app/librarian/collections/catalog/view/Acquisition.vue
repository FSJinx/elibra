<template>
  <div class="size-full flex flex-col gap-3">

    <Table title="Acquistion History" subtitle="These are the acquisition history of each copies of this material" :data-length="acquisitions?.length">
      <Thead>
        <tr>
          <Th class="max-w-50">No</Th>
          <Th class="text-left">Reference No</Th>
          <Th class="text-left">Dealer</Th>
          <Th>Acquistion Mode</Th>
          <Th>Date Acquired</Th>
          <Th>Received By</Th>
          <Th>Quantity</Th>
          <Th>Unit Price</Th>
          <Th>Total Price</Th>
        </tr>
      </Thead>
      <Tbody :data="acquisitions" :loading="loading" cols="9">
        <Tr v-for="(item, index) in acquisitions" :key="index">
          <Td>{{ index + 1 }}</Td>
          <Td class="text-left" :data="item.reference" />
          <Td class="text-left" :data="item.dealer" />
          <Td class="capitalize" :data="item.acquisition_mode" />
          <Td :data="parse.formatDate(item.acquisition_date)" />
          <Td :data="parse.name(item.receiver).full" />
          <Td :data="item.acquisition_lines.quantity" />
          <Td :data="item.acquisition_lines.unit_price" />
          <Td :data="Number(item.acquisition_lines.unit_price) * Number(item.acquisition_lines.quantity)" />
        </Tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ data: Item }>()

const { fetchResource } = useItemStore()
const options = optionStore()
const parse = useParser()

const acquisitions = ref<Acquisition[] | null>()
const loading = ref<boolean>(false)

async function fetchData() {
  loading.value = true
  try {
    const res = await fetchResource('acquisitions', props.data.id)
    acquisitions.value = res.data
  } finally {
    loading.value = false
  }
}
onBeforeMount(fetchData)
</script>

<style scoped></style>
