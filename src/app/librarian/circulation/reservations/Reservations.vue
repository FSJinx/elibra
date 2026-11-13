<template>
  <div class="size-full flex flex-col gap 5 overflow-hidden">
    <SectionHeader title="Holds & Reservations" description="Manage reservations for items in your library" icon=""></SectionHeader>

    <div class="flex-1 flex flex-col p-5 overflow-hidden">
      <Table title="Pending Holdings" subtitle="Here are the reservations made by patrons" :data="pagination">
        <template #header>
          <Form class="flex flex-wrap items-center justify-end w-full gap-2" id="holding-form" @submit="search">
            <Input class="max-w-120" id="holding-query" type="text" placeholder="Search by accession number, reservation id, or patron" v-model="params.query" />
            <Button type="submit">Search</Button>
          </Form>
        </template>
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession Number</Th>
            <Th class="text-left">Title</Th>
            <Th class="text-left">Reserved By</Th>
            <Th>Date of Pickup</Th>
            <Th>Status</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="holding.data" :loading>
          <Tr v-for="(item, index) in holding.data" :key="item.id">
            <Td :data="index + 1" />
            <Td :data="item.accession?.accession_number" />
            <Td class="text-left" :data="item.accession?.item?.title" />
            <Td class="text-left" :data="item.reserver?.user?.name?.full" />
            <Td :data="parse.formatDateTime(item.expected_pickup_date)" />
            <Td>
              <Status :variant="item.status">{{ item.status }}</Status>
            </Td>
            <Td class="space-x-2">
              <Complete v-if="item.status !== 'completed' && !item.pickup_date" :data="item" @refresh="search" />
              <Button class="hover:text-danger" size="sm" :loading="deletingId === item.id" :disabled="deletingId !== null" @click="deleteReservation(item)">Delete</Button>
            </Td>
          </Tr>
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import Complete from '@/app/librarian/circulation/reservations/modals/Complete.vue'

const holding = holdingStore()
const pop = usePopup()
const parse = useParser()
const deletingId = ref<Holding['id'] | null>(null)

const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const params = reactive<Partial<HoldingParams>>({})

async function search() {
  loading.value = true
  try {
    const res = await holding.fetch()
    pagination.value = res.data
  } finally {
    loading.value = false
  }
}

async function deleteReservation(h: Holding) {
  const confirm = await pop.confirm({ text: 'Delete this reservation? By proceeding, this will cancel and delete the reservation' })

  if (confirm.isConfirmed) {
    deletingId.value = h.id
    pop.load()
    try {
      const res = await holding.deleteHolding(h)
      pop.unload()
      pop.success(res.message)
    } catch (e: any) {
      pop.unload()
      pop.error(e.response?.data?.message ?? 'Unable to delete this reservation.')
    } finally {
      deletingId.value = null
    }
  }
}

watchDebounced(() => params, search, { deep: true, immediate: true, debounce: 300 })
</script>

<style scoped></style>
