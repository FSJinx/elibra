<template>
  <Button class="hover:text-info" size="sm" @click="open">Edit</Button>

  <Modal ref="modal" size="normal">
    <ModalHeader use-default-layout :title="data?.item?.title" :subtitle="data?.item?.subtitle" icon="book" />
    <ModalBody class="space-y-3 p-4">
      <p class="uppercase font-medium text-sm tracking-wide text-muted-foreground">Update Information</p>
      <Form class="space-y-3" id="update-item-accession" @submit="submit">
        <Control id="" col>
          <Label>Quantity</Label>
          <Input id="" type="number" placeholder="Enter quantity..." v-model="form.quantity" />
        </Control>
        <Control id="" col>
          <Label>Unit Price</Label>
          <Input id="" type="number" placeholder="Enter unit price..." v-model="form.unit_price" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="update-item-accession" variant="success" type="submit">Update</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()

const props = withDefaults(defineProps<{ data: any }>(), {})

const modal = ref<InstanceType<typeof Modal>>()
const form = ref({
  quantity: props?.data?.quantity ?? '',
  unit_price: props?.data?.unit_price ?? 0,
})

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  // updating item
  pop.info("Oops, this feature isn't up yet. Please come back later.")
}
</script>

<style scoped></style>
