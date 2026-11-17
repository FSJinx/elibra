<template>
  <div class="size-full flex flex-col">
    <SectionHeader title="Publishers" description="Manage the list of publishers" icon="shop">
      <div class="flex items-center justify-end">
        <Button variant="primary" icon="plus-lg">New Publisher</Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-3 overflow-hidden">
      <Card>
        <CardBody class="grid grid-cols-2 items-center gap-2">
          <div class="flex items-center gap-2">
            <Input id="" type="text" class="min-w-100" placeholder="Search for publisher by name..." v-model="params.query" />
            <Button @click="search">Search</Button>
            <Select title="Sort By">
              <Option value="" selected disabled>Select Sort</Option>
              <Option value="name">Name</Option>
              <Option value="created_at">Date Added</Option>
            </Select>
            <Select title="Order">
              <Option value="" selected disabled>Select Order</Option>
              <Option value="asc">Ascending</Option>
              <Option value="desc">Descending</Option>
            </Select>
          </div>
        </CardBody>
      </Card>

      <div class="flex-1 flex overflow-y-auto">
        <Table title="Publishers Table" subtitle="List of publishers">
          <Thead>
            <tr>
              <Th>No.</Th>
              <Th class="text-left">Name</Th>
              <Th class="text-left">Address</Th>
              <Th>Date Added</Th>
              <Th>Actions</Th>
            </tr>
          </Thead>
          <Tbody :data="publisher.data" :loading="loading" cols="5">
            <tr v-for="(item, index) in publisher.data" :key="index">
              <Td :data="index + 1" />
              <Td class="text-left" :data="item.name" />
              <Td class="text-left" :data="item.address" />
              <Td :data="parse.formatDate(item.created_at)" />
              <Td class="text-muted-foreground">No available actions yet.</Td>
            </tr>
          </Tbody>
        </Table>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const publisher = publisherStore()
const parse = useParser()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)
const params = ref<PublisherParams>({
  query: '',
})

async function search() {
  loading.value = true
  try {
    const res = await publisher.fetch(params.value)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

watchDebounced(params, search, { deep: true, immediate: true, debounce: 300 })
onBeforeUnmount(() => publisher.data = null)
</script>

<style scoped></style>
