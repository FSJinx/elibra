<template>
  <Button variant="primary" icon="plus-lg" @click="open">New Inventory</Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout title="New Inventory" subtitle="Monitor and check your library's stock" icon="boxes" />

    <ModalBody>
      <Form class="p-5" id="new-inventory">
        <Control id="inventory-section" required col>
          <div class="">
            <Label> Section </Label>
            <p class="text-muted-foreground text-sm">Select a section to inventory</p>
          </div>
          <Select title="Sections">
            <Option value="" selected disabled>Select a section to inventory</Option>
            <Option :value="section.id" v-for="section in options.sections?.filter((i) => i.library_id === auth.user?.library?.id)">{{ section.name }}</Option>
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
const options = optionStore()
const auth = authStore()

function open() {
  modal.value?.open()
}

onBeforeMount(() => options.fetch('sections'))
</script>

<style scoped></style>
