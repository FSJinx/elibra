<template>
  <div class="size-full flex flex-col gap-3">
    <div class="action-buttons flex justify-end gap-2">
      <Button variant="primary" left-icon="plus-lg">New Copy</Button>
    </div>

    <Table title="Authors" subtitle="Authors who have contributed to this material" :data-length="accessions?.length">
      <Thead>
        <tr>
          <Th class="max-w-50">No</Th>
          <Th class="text-left">Accession Code</Th>
          <Th>Status</Th>
          <Th>Shelf Location</Th>
        </tr>
      </Thead>
      <Tbody :data="accessions" :loading="loading" cols="4">
        <Tr v-for="(item, index) in accessions" :key="index">
          <Td>{{ index + 1 }}</Td>
          <Td class="text-left" :data="item.accession_number" />
          <Td>
            <Status :variant="item.status">{{ item.status }}</Status>
          </Td>
          <Td :data="item.section_id ? section(item.section_id)?.name : null" />
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

const accessions = ref<Accession[] | null>()
const loading = ref<boolean>(false)

const section = computed(() => (id: any) => options.sections?.find((i) => i.id == id))

async function fetchData() {
  loading.value = true
  try {
    const res = await fetchResource('accessions', props.data.id)
    accessions.value = res.data
  } finally {
    loading.value = false
  }
}
onBeforeMount(fetchData)
</script>

<style scoped></style>
