<template>
  <Issue @refresh="" />

  <Card>
    <CardBody class="flex items-center gap-2 justify-between">
      <Form class="flex items-center gap-3 w-full max-w-150 mr-auto" id="loan-search" @submit="search">
        <Input id="" type="text" placeholder="Search by accession number, patron name, or patron id" v-model="params.query" />
        <Button form="loan-search" type="submit">Search</Button>
      </Form>

      <Select class="max-w-max" v-model="params.sort">
        <Option value="">Default</Option>
        <Option value="status">Status</Option>
      </Select>

      <Select class="max-w-max" v-model="params.order">
        <Option value="asc">Ascending</Option>
        <Option value="desc">Descending</Option>
      </Select>
    </CardBody>
  </Card>

  <Table title="Current Loans" subtitle="These are the recent check outs of your library" :data="pagination" @per_page="params.per_page = $event" @next="params.page = $event" @prev="params.page = $event">
    <Thead>
      <tr>
        <Th>No</Th>
        <Th class="text-left">Title</Th>
        <Th>Accession</Th>
        <Th class="text-left">Borrower</Th>
        <Th>Borrowed At</Th>
        <Th>Due Date</Th>
        <Th>Status</Th>
        <Th>Actions</Th>
      </tr>
    </Thead>
    <Tbody :data="loan.data" :loading="loading">
      <Tr v-for="(item, index) in loan.data" :key="index">
        <Td :data="index + 1" />
        <Td :data="item.accession?.item?.title" />
        <Td :data="item.accession?.accession_number" />
        <Td>{{ item.patron?.user?.name?.full }} ({{ item.patron?.user?.username }})</Td>
        <Td :data="parse.formatDate(item.borrowed_at)" />
        <Td :data="parse.formatDate(item.due_at)" />
        <Td>
          <Status :variant="item?.status">{{ item?.status }}</Status>
        </Td>
        <Td>
          <Button class="hover:text-info" size="sm">Edit</Button>
          <Button class="hover:text-danger" size="sm">Delete</Button>
        </Td>
      </Tr>
    </Tbody>
    <template #footer />
  </Table>
</template>

<script setup lang="ts">
import Issue from '@/app/librarian/circulation/loans/issue/Issue.vue'

const loan = loanStore()
const parse = useParser()
const auth = authStore()

const loading = ref<boolean>(false)
const pagination = ref<Pagination>()

const defaultParams = (): Partial<LoanParams> => ({
  query: '',
  order: 'asc',
  library_id: auth.user?.library?.id ?? null,
})
const params = ref(defaultParams())

async function search() {
  loading.value = true
  try {
    const res = await loan.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watch(params, search, { deep: true })

onBeforeMount(search)
</script>

<style scoped></style>
