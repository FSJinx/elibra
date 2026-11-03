<template>
  <Button variant="primary" left-icon="plus-lg" @click="open">New Copy</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="New Copy" :subtitle="`Add new copies for ${item.currentData?.title}`" icon="journal-plus" />
    <ModalBody class="p-5">
      <Form id="accession-new-copies" @submit="submit">
        <Control id="new-section" col nullable>
          <Label>Shelf Location</Label>
          <Select v-model="form.section_id">
            <Option value="" selected disabled>Select where this copies will be located</Option>
            <Option :value="item.id" v-for="item in options.sections?.filter((i) => i.library_id === auth.user?.library?.id)">{{ item.name }}</Option>
          </Select>
        </Control>
        <Control id="new-quantity" col required>
          <Label>Quantity</Label>
          <Input type="number" placeholder="" min="1" v-model="form.quantity" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="accession-new-copies" variant="success" type="submit">Submit</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const emit = defineEmits(['refresh'])

const item = itemStore()
const accession = accessionStore()
const pop = usePopup()
const options = optionStore()
const auth = authStore()

const modal = ref<InstanceType<typeof Modal>>()
const form = ref<StoreAccession>({
  item_id: item.currentData?.id,
  acquisition_line_id: null,
  quantity: 0,
  section_id: '',
})

function open() {
  modal.value?.open()
  form.value.quantity = 0
}

function close() {
  modal.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: `You are currently adding ${form.value?.quantity} new copies of ${item.currentData?.title}. Confirm to proceed` })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await accession.create(form.value)
      pop.success(res?.message)
      nextTick(close)
      return emit('refresh')
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>

<style scoped></style>
