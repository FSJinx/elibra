<template>
  <div class="size-full flex flex-col gap 5 overflow-hidden">
    <SectionHeader title="Holds & Reservations" description="Manage reservations for items in your library" icon=""></SectionHeader>

    <div class="flex-1 flex flex-col p-5">
      <Table title="Pending Holdings" subtitle="Here are the reservations made by patrons">
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
        <Tbody :data="holding.data" :loading="holding.loading" cols="7">
          <Tr v-for="(item, index) in holding.data" :key="item.id">
            <Td :data="index + 1" />
            <Td :data="item.accession?.accession_number" />
            <Td class="text-left" :data="item.accession?.item?.title" />
            <Td class="text-left" :data="item.reserver?.user?.name?.full" />
            <Td :data="parse.formatDateTime(item.pickup_date)" />
            <Td>
              <Status :variant="item.picked_up_at ? 'success' : 'warning'">{{ item.picked_up_at ? 'Completed' : 'Pending' }}</Status>
            </Td>
            <Td class="space-x-2">
              <Complete v-if="!item.picked_up_at" :data="item" @refresh="fetchReservations" />
              <Button class="hover:text-danger" size="sm" :loading="deletingId === item.id" :disabled="deletingId !== null" @click="deleteReservation(item)">Delete</Button>
            </Td>
          </Tr>
          <Tr v-if="holding.data?.length === 0">
            <Td colspan="7" class="px-5 py-10 text-center text-sm">No reservations found.</Td>
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

async function fetchReservations() {
  try {
    await holding.fetch()
  } catch (error: any) {
    await pop.error(error?.response?.data?.message ?? 'Unable to load reservations. Please try again.')
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

onBeforeMount(fetchReservations)
</script>

<style scoped></style>
