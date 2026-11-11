<template>
  <Button variant="primary" icon="plus-lg" @click="open">New Inventory</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="New Inventory" subtitle="Monitor and check your library's stock" icon="boxes" />

    <ModalBody>
      <Form class="p-5 space-y-3" id="new-inventory" @submit="submit">
        <Control id="inventory-section" required col>
          <Label>Shelf Status</Label>
          <Select title="Sections" v-model="form.shelved">
            <Option value="" selected disabled>Select shelf status</Option>
            <Option :value="true" label="On Shelf" />
            <Option :value="false" label="Unshelved" />
          </Select>
        </Control>
        <Control id="inventory-section" required col v-if="form.shelved">
          <div class="">
            <Label> Section </Label>
            <p class="text-muted-foreground text-sm">Select a section to inventory</p>
          </div>
          <Select title="Sections" v-model="form.section_id">
            <Option value="" selected disabled>Select a section to inventory</Option>
            <Option :value="section.id" v-for="section in options.sections?.filter((i) => i.library_id === auth.user?.library?.id)">{{ section.name }}</Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>

    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button type="submit" form="new-inventory" variant="primary">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const options = optionStore()
const auth = authStore()
const pop = usePopup()
const inventory = inventoryStore()

const emit = defineEmits(['refresh'])

const defaultForm = (): StoreInventory => ({
  shelved: null,
  section_id: '',
})

const modal = ref<InstanceType<typeof Modal>>()
const form = ref<StoreInventory>(defaultForm())

function open() {
  modal.value?.open()
}

function close() {
  Object.assign(form, defaultForm())
  modal.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: 'By proceeding, you will start an inventory tracking for this section' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await inventory.create(form.value)
      pop.success(res.message)
      emit('refresh')
      close()
    } catch (e: any) {
      pop.error(e?.response?.data?.message ?? 'Something went wrong, please try again')
    }
  }
}

onBeforeMount(() => options.fetch('sections'))
</script>

<style scoped></style>
