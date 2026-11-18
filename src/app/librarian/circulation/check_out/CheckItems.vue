<template>
  <!-- Searching Items -->
  <Table title="Items" subtitle="Preview items before adding to book bag" :data="pagination" @per_page="params.per_page = $event" @page="params.page = $event">
    <template #header>
      <Form class="flex items-center justify-end gap-2 w-full" id="search-item" @submit="search">
        <Input class="max-w-150" type="text" enable-clear placeholder="Search by item title, accession number, call number" v-model="params.query" />
        <Button form="search-item" type="submit">Search</Button>
      </Form>
    </template>
    <Thead>
      <tr>
        <Th>No</Th>
        <Th class="text-left">Title</Th>
        <Th>Item Type</Th>
        <Th>Call Number</Th>
        <Th>Action</Th>
      </tr>
    </Thead>
    <Tbody :data="item.data" :loading="loading" error-message="No item in the preview, search now.">
      <Tr v-for="(item, index) in item.data" :key="index">
        <Td :data="index + 1" />
        <Td class="text-left" :data="item.title" />
        <Td :data="item.item_type?.name" />
        <Td :data="item.call_number" />
        <Td>
          <ViewAccessions v-model="model" :item="item" @open-bag="emit('openBag')" />
        </Td>
      </Tr>
    </Tbody>
    <template #footer />
  </Table>
</template>

<script setup lang="ts">
import ViewAccessions from '@/app/librarian/circulation/check_out/modals/ViewAccessions.vue'

const model = defineModel<Accession[] | null>({ default: () => [] })
const emit = defineEmits(['openBag'])

// const accession = accessionStore()
const item = itemStore()
const auth = authStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = ref<Partial<ItemParams>>({
  query: '',
  loanable: true,
  library_id: auth.user?.library?.id,
})

async function search() {
  loading.value = true
  try {
    const res = await item.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watchDebounced(params, search, { debounce: 300, immediate: true, deep: true })
</script>

<style scoped></style>
