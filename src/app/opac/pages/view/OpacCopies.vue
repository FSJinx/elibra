<template>
  <p class="text-center" v-if="!auth.isAuthenticated">You need to be a logged in user to check this item's copies.</p>
  <Table title="Available Copies" subtitle="" :data="pagination" v-else>
    <template #header>
      <Button @click="search">Refresh</Button>
    </template>
    <Thead>
      <tr>
        <Th>No</Th>
        <Th class="text-left">Accession Number</Th>
        <Th>Status</Th>
        <Th>Actions</Th>
      </tr>
    </Thead>
    <Tbody :data="pagination?.data" :loading error-message="No available copies yet">
      <Tr v-for="(item, index) in pagination?.data">
        <Td :data="pagination?.from + index" />
        <Td class="text-left" :data="item?.accession_number" />
        <Td>
          <Status :variant="item.status">{{ item.status }}</Status>
        </Td>
        <Td>
          <Button @click="openReservation(item)" variant="info" size="sm" v-if="item?.status === 'available'">Reserve</Button>
          <p v-else>No actions available</p>
        </Td>
      </Tr>
    </Tbody>
  </Table>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Reservation" subtitle="Please pick a date of pickup for the item you're reserving. " icon="calendar" />
    <ModalBody class="p-5">
      <Form id="reservation-form" @submit="reserve">
        <Control id="form-pickup_date" col required>
          <Label> Date of Pickup</Label>
          <DatePicker v-model="form.expected_pickup_date" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button @click="close" variant="danger">Cancel</Button>
      <Button form="reservation-form" variant="success" type="submit">Reserve</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import { StoreHolding } from '@/interfaces/holding'

const props = defineProps<{ data: Opac | null }>()

const auth = authStore()
const pop = usePopup()
const accession = accessionStore()
const holding = holdingStore()

const params = reactive<Partial<AccessionParams>>({
  loanable: true,
})
const modal = ref<InstanceType<typeof Modal>>()
const loading = ref<boolean>(false)
const pagination = ref<Pagination>()

async function search() {
  loading.value = true
  try {
    const res = await accession.fetch(params)
    pagination.value = res.data
  } catch (e: any) {
    throw e
  } finally {
    loading.value = false
  }
}

// =============== Reservation Business Logics

const defaultForm = (): StoreHolding => ({
  reserver_id: auth.user?.patron?.id ?? null,
  accession_id: '',
  expected_pickup_date: new Date().toLocaleDateString('en-PH'),
})
const form = reactive<StoreHolding>(defaultForm())

function resetForm() {
  Object.assign(form, defaultForm())
}

function openReservation(item: Accession) {
  Object.assign(form, {
    accession_id: item.id,
  })

  modal.value?.open()
}

function close() {
  resetForm()
  modal.value?.close()
}

async function reserve(item: Accession) {
  const confirm = await pop.confirm({ text: 'Are you sure you want to reserve this? You will be required to present yourself at your set pickup data.' })

  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await holding.create(form)
    pop.success(res.messsage)
    search()
    close()
  } catch (e: any) {
    throw e
  }
}

onBeforeMount(search)
</script>

<style scoped></style>
