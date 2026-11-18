<template>
  <Button size="sm" variant="info" @click="open">View</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout :title="item.title" :subtitle="`Viewing copies of ${item.title}`" icon="book" />
    <ModalBody class="">
      <Table :data="pagination">
        <template #header>
          <Button class="ml-auto" @click="search">Refresh</Button>
        </template>
        <Thead>
          <tr>
            <Th>No</Th>
            <Th>Accession Number</Th>
            <Th>Action</Th>
          </tr>
        </Thead>
        <Tbody :data="accession.data" :loading error-message="No available copies for this item.">
          <Tr v-for="(item, index) in accession.data" :key="index">
            <Td></Td>
            <Td></Td>
          </Tr>
        </Tbody>
      </Table>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ item: Item }>()

const accession = accessionStore()

const modal = ref<InstanceType<typeof Modal>>()
const pagination = ref<Pagination>()
const loading = ref<boolean>(false)

function open() {
  modal.value?.open()
}

async function search() {}
</script>

<style scoped></style>
