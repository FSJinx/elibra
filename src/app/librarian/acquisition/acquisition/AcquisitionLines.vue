<template>
  <div class="size-full flex flex-col">
    <div class="flex items-center bg-background p-4 pb-0">
      <Button variant="text" @click="router.back()" icon="arrow-left">
        <span class="text-lg">Back to Acquisition Record</span>
      </Button>
    </div>
    <div class="grid grid-cols-2 p-7 bg-background border-b border-border">
      <div class="">
        <p class="font-semibold text-primary uppercase tracking-wider">{{ currentData?.acquisition_mode }}</p>
        <H5>Acquisiton ID#: {{ currentData?.acquisition_id }}</H5>
        <p>Date of Acquisition: {{ parse.formatDate(currentData?.acquisition_date) }}</p>
      </div>
      <div class="flex items-end justify-end gap-2">
        <AddNewAcquisitionItem />
      </div>
    </div>

    <div class="flex-1 flex p-5 -mt-2">
      <Table title="Acquired Items" subtitle="Items in this purchase">
        <Thead>
          <tr>
            <Th class="text-left">Title</Th>
            <Th>Quantity</Th>
            <Th>Unit Price</Th>
            <Th>Discount</Th>
            <Th>Net Price</Th>
          </tr>
        </Thead>
        <Tbody :data="lines.data" :loading="lines.loading" :cols="5">
          <tr class="hover" v-for="(item, index) in lines.data" @click="view(item)">
            <Td class="text-left">
              <p class="font-medium">{{ item.items?.title }}</p>
              <p class="text-xs text-muted-foreground">{{ item.items?.subtitle }}</p>
            </Td>
            <Td :data="item.quantity" />
            <Td :data="parse.toMoney(item.unitPrice)" />
            <Td :data="parse.toMoney(item.discount)" />
            <Td :data="parse.toMoney(item.netPrice)" />
          </tr>
        </Tbody>
      </Table>
    </div>
  </div>

  <Modal ref="viewModal" size="2xlarge">
    <ModalHeader useDefaultLayout :title="lines.currentData?.items?.title" />
    <ModalBody class="flex flex-col overflow-hidden!">
      <Form class="p-5 border-b border-border">
        <div class="flex items-center justify-end gap-1">
          <Input id="search-accession" type="text" placeholder="Search by accession number..." class="max-w-100" />
          <Button>Search</Button>
        </div>
      </Form>
      <div class="overflow-y-auto">
        <div class="h-screen"></div>
      </div>
    </ModalBody>
  </Modal>
</template>

<script setup lang="ts">
import AddNewAcquisitionItem from '@/app/librarian/acquisition/acquisition/modals/AddNewAcquisitionItem.vue'
import Modal from '@/components/my/Modal.vue'

const parse = useParser()

const { currentData } = useAcquisitionStore()
const lines = useAcquisitionLinesStore()

const viewModal = ref<typeof Modal | null>(null)

function view(item: AcquisitionLines) {
  viewModal.value?.open()
  lines.setCurrentData(item)
}
</script>

<style scoped></style>
