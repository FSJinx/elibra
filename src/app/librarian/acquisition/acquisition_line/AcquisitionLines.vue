<template>
  <div class="size-full flex flex-col justify-center text-center" v-if="!acquisition.currentData">Acquisition not found.</div>
  <div class="size-full flex flex-col overflow-hidden" v-else>
    <div class="flex items-start bg-background p-5 gap-2 border-b border-border">
      <Button variant="text" @click="router.back()" icon="arrow-left" />
      <div class="">
        <H6 class="flex items-center gap-3">
          Acquisiton ID#: {{ acquisition.currentData.reference }}
          <span class="font-semibold bg-primary-soft text-primary rounded-lg uppercase tracking-wider text-xs p-2">{{ acquisition.currentData?.acquisition_mode?.name }}</span>
        </H6>
        <p>Date of Acquisition: {{ parse.formatDate(acquisition.currentData?.acquisition_date) }}</p>
      </div>
      <div class="flex items-center ml-auto gap-2">
        <SearchItem @update:Lines="search" />
        <Button variant="primary" as="link" :to="{ name: 'librarian.collections.catalog.new', query: { acquisition: true, acquisition_id: acquisition.currentData.id } }">New Item</Button>
      </div>
    </div>

    <div class="flex-1 flex flex-col gap-3 overflow-y-auto p-5">
      <Card class="">
        <CardBody class="grid grid-cols-4 gap-2">
          <Control col>
            <Label class="uppercase text-sm text-muted-foreground tracking-wide">Acquisition From</Label>
            <Title>{{ acquisition.currentData?.dealer }}</Title>
          </Control>
          <Control col>
            <Label class="uppercase text-sm text-muted-foreground tracking-wide">Acquisition Mode</Label>
            <Title class="uppercase text-success tracking-wide">{{ acquisition.currentData?.acquisition_mode?.name }}</Title>
          </Control>
          <Control col>
            <Label class="uppercase text-sm text-muted-foreground tracking-wide">Date Received</Label>
            <Title>{{ parse.formatDateAgo(acquisition.currentData?.acquisition_date) }}</Title>
          </Control>
          <Control col>
            <Label class="uppercase text-sm text-muted-foreground tracking-wide">Added By</Label>
            <Title>{{ acquisition.currentData?.receiver?.name?.full }}</Title>
          </Control>
        </CardBody>
      </Card>

      <Table title="Acquired Items" :subtitle="`Here are the items that is acquired from ${acquisition.currentData?.dealer} through ${acquisition.currentData?.acquisition_mode?.name}.`" :data="pagination">
        <Thead>
          <tr>
            <Th>No</Th>
            <Th class="text-left">Title</Th>
            <Th>Quantity</Th>
            <Th v-if="acquisition.currentData?.acquisition_mode?.has_price">Unit Price</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="lines.data" :loading="loading">
          <Tr class="hover" v-for="(item, index) in lines.data">
            <Td :data="index + 1" />
            <Td class="text-left">
              <p class="font-medium">{{ item.item.title }}</p>
              <p class="text-xs text-muted-foreground">{{ item.item.subtitle }}</p>
            </Td>
            <Td :data="item.quantity" />
            <Td v-if="acquisition.currentData?.acquisition_mode?.has_price" :data="parse.toMoney(item.unitPrice)" />
            <Td class="space-x-2">
              <EditItemAcquisition :data="item" :acquisition="acquisition.currentData" @refresh="search" />
              <Button class="hover:text-danger" size="sm" @click="del(item)">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
        <template #footer />
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import EditItemAcquisition from '@/app/librarian/acquisition/acquisition_line/modals/EditItemAcquisition.vue'
import SearchItem from '@/app/librarian/acquisition/acquisition_line/modals/SearchItem.vue'

const parse = useParser()
const pop = usePopup()

const acquisition = acquisitionStore()
const lines = acquisitionLinesStore()

const pagination = ref<Pagination>()
const loading = ref<boolean>(false)

async function search() {
  loading.value = true
  try {
    const res = await lines.fetch(acquisition.currentData?.id)
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function del(l: AcquisitionLine) {
  const confirm = await pop.confirm({ text: `Are you sure you want to delete "${l.item?.title}" from this acquisition?` })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await lines.destroy(l)

      if (res.status === 'success') {
        search()
        return pop.success(res.message)
      } else return pop.success(res.message)
    } catch (e: any) {
      pop.error(e.response?.data?.message ?? 'Something went wrong deleting the item from the record')
    }
  }
}

onMounted(search)
onBeforeUnmount(() => {
  acquisition.currentData = null
  lines.data = null
})
</script>

<style scoped></style>
