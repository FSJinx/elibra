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
        <Tbody :data="[{}]" :loading="false">
          <!-- Sample Data -->
          <Tr>
            <Td>1</Td>
            <Td>GC034834</Td>
            <Td class="text-left">Sample Book Title</Td>
            <Td class="text-left">Juan Dela Cruz</Td>
            <Td>October 29, 2026 - 9:00 AM</Td>
            <Td>
              <Status variant="completed">Completed</Status>
            </Td>
            <Td class="space-x-2">
              <Complete :data="{} as Holding" />
              <Button class="hover:text-danger" size="sm" @click="deleteReservation({} as Holding)">Delete</Button>
            </Td>
          </Tr>
          <!-- <Tr v-for="(item, index) in " :key="index">
            <Td :data="index + 1" />
            <Td></Td>
          </Tr> -->
        </Tbody>
      </Table>
    </div>
  </div>
</template>

<script setup lang="ts">
import Complete from '@/app/librarian/circulation/reservations/modals/Complete.vue'

const holding = holdingStore()
const pop = usePopup()

async function deleteReservation(h: Holding) {
  const confirm = await pop.confirm({ text: 'Delete this reservation? By proceeding, this will cancel and delete the reservation' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await holding.deleteHolding(h)
      pop.success(res.message)
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>

<style scoped></style>
