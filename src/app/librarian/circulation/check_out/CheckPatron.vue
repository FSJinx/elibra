<template>
  <!-- Searching Patron -->
  <Card>
    <CardBody>
      <Form class="flex items-center gap-2 w-full" id="search-patron" @submit="search">
        <Input class="max-w-150" type="text" enable-clear placeholder="Search by Patron Name, ID Number, or EBC Number" v-model="searchPatron" />
        <Button form="search-patron" variant="success" type="submit">Search</Button>
      </Form>
    </CardBody>
  </Card>

  <Table title="Patrons" subtitle="Preview patron's information for this borrow" :data="pagination">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th class="text-left">Name</Th>
        <Th>ID Number / Username</Th>
        <Th>EBC Number</Th>
        <Th>Patron Type</Th>
        <Th>Action</Th>
      </tr>
    </Thead>
    <Tbody :data="patron.data" :loading="loading" error-message="No patron in the preview, search now.">
      <Tr v-for="(item, index) in patron.data" :key="index" @click="assignBorrower(item)">
        <Td :data="index + 1" />
        <Td class="text-left" :data="item.user?.name?.full" />
        <Td :data="item.user?.username" />
        <Td :data="item.ebc_number" />
        <Td :data="item.patron_type?.name" />
        <Td>
          <Button size="sm" class="hover:text-info" @click="assignBorrower(item)">Assign</Button>
        </Td>
      </Tr>
    </Tbody>
  </Table>
</template>

<script setup lang="ts">
const model = defineModel<Patron | null>({ default: null })

const patron = patronStore()
const pop = usePopup()

const pagination = ref<Pagination>()
const searchPatron = ref()
const loading = ref<boolean>(false)

async function search() {
  loading.value = true
  try {
    const res = await patron.fetch({ query: searchPatron.value })
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function assignBorrower(patron: Patron) {
  const confirm = await pop.confirm({ text: 'Assign this patron as borrower?' })

  if (confirm.isConfirmed) {
    model.value = patron
    // pop.success('Patron assigned successfully!')
  }
}

watchDebounced(searchPatron, () => search(), { debounce: 300, immediate: true })
</script>

<style scoped></style>
