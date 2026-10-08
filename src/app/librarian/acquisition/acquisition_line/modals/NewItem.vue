<template>
  <Button variant="primary" @click="open">New Item</Button>

  <Modal ref="modal" size="xlarge" class="rounded-none! border-none!">
    <ModalHeader use-default-layout title="New Item" subtitle="Create a new item for this acquisition" icon="journal-plus" />

    <ModalBody>
      <Form id="acquisition-item">
        <BaseSection v-model="form" :errors="errors" />
      </Form>
    </ModalBody>

    <ModalFooter> </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import { emptyForm } from '@/app/librarian/collections/catalog/forms/form'
import BaseSection from '@/app/librarian/collections/catalog/forms/sections/BaseSection.vue'
import Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()
const form = reactive(emptyForm())
const errors = reactive(emptyForm())
function open() {
  modal.value?.open()
}
</script>

<style scoped></style>
