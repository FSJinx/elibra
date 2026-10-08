<template>
  <div class="size-full flex flex-col justify-center" v-if="!currentData">Acquisition not found.</div>
  <div class="size-full flex flex-col" v-else>
    <div class="flex items-start bg-background p-5 gap-2 border-b border-border">
      <Button variant="text" @click="router.back()" icon="arrow-left" />
      <div class="">
        <H6 class="flex items-center gap-3">
          Acquisiton ID#: {{ currentData?.acquisition_id }}
          <span class="font-semibold bg-primary-soft text-primary rounded-lg uppercase tracking-wider text-xs p-2">{{ currentData?.acquisition_mode }}</span>
        </H6>
        <p>Date of Acquisition: {{ parse.formatDate(currentData?.acquisition_date) }}</p>
      </div>
      <div class="flex items-center ml-auto gap-2">
        <SearchItem />
        <Button variant="primary" as="link" :to="{ name: 'librarian.collections.catalog.new', query: { acquisition: true, acquisition_id: currentData.id } }">New Item</Button>

        <!-- <NewItem /> -->
      </div>
    </div>

    <div class="flex-1 flex p-5">
      <Table title="Acquired Items" :subtitle="`Here are the items that is acquired from ${currentData?.dealer} through ${currentData?.acquisition_mode}.`">
        <Thead>
          <tr>
            <Th class="text-left">Title</Th>
            <Th>Quantity</Th>
            <Th>Unit Price</Th>
            <Th>Actions</Th>
          </tr>
        </Thead>
        <Tbody :data="lines.data" :loading="lines.loading" :cols="4">
          <tr class="hover" v-for="(item, index) in lines.data" @click="view(item)">
            <Td class="text-left">
              <p class="font-medium">{{ item.items?.title }}</p>
              <p class="text-xs text-muted-foreground">{{ item.items?.subtitle }}</p>
            </Td>
            <Td :data="item.quantity" />
            <Td :data="parse.toMoney(item.unitPrice)" />
            <Td />
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
import SearchItem from '@/app/librarian/acquisition/acquisition_line/modals/SearchItem.vue'
import Modal from '@/components/my/Modal.vue'

const parse = useParser()

const { currentData } = useAcquisitionStore()
const lines = useAcquisitionLinesStore()

const viewModal = ref<typeof Modal | null>(null)

function view(item: AcquisitionLine) {
  viewModal.value?.open()
  lines.setCurrentData(item)
}

onBeforeMount(async () => {})
</script>

<style scoped></style>
