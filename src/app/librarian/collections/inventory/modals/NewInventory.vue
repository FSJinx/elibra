<template>
  <Button variant="primary" icon="plus-lg" @click="open">New Inventory</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="New Inventory" subtitle="Monitor and check your library's stock" icon="boxes" />

    <ModalBody>
        <Form class="p-5" id="new-inventory">
            <h2 class="uppercase tracking-wide font-semibold mb-5">Select a Section</h2>
            <Control id="inventory-section" required col>
                <Label>Section</Label>
                <Select title="Sections">
                    <Option value="" selected disabled>Select a section to inventory</Option>
                    <Option value="" selected disabled>Select a section to inventory</Option>

                </Select>
            </Control>
        </Form>
    </ModalBody>
    
    <ModalFooter>
        <Button type="submit" form="new-inventory" variant="primary">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()

function open() {
  modal.value?.open()
}
</script>

<style scoped></style>
