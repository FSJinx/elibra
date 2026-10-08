<template>
  <Button @click="open" data-title="Search item in the catalog for this acquisition">Search Catalog</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="Search Catalog" subtitle="Search an item through the catalog and add to this acquisition" icon="search"/>

    <ModalBody>
      <Form id="acquisition-item"> </Form>
    </ModalBody>

    <ModalFooter> </ModalFooter>
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
