<template>
  <Button variant="primary" left-icon="plus-lg" @click="modal?.open()">New Item Type</Button>

  <Modal ref="modal" size="large" :has-inputs="hasInput">
    <ModalHeader use-default-layout title="New Item Type" subtitle="Add new item type to the list" icon="tags" />
    <ModalBody>
      <Form id="new-item_type" class="flex flex-col gap-5 p-5" @submit="handleSubmit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Item Type Information</h1>

        <div class="grid grid-cols-3 gap-5">
          <Control id="item_type-name" col required class="col-span-2">
            <Label>Name</Label>
            <Input type="text" placeholder="e.g. Book" v-model="model.name" />
          </Control>
          <Control id="item_type-code" col required>
            <Label>Code</Label>
            <Input type="text" placeholder="e.g. Book" v-model="model.slug" />
          </Control>

          <Checkbox id="item_type-loanable" label="Available for loans" v-model="model.loanable" />
        </div>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close()">Cancel</Button>
      <Button type="submit" variant="success" form="new-item_type">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const modal = ref<InstanceType<typeof Modal>>()
const hasInput = computed(() => model.name !== '' || model.slug !== '')

const defaultValue = (): Partial<ItemType> => ({
  name: '',
  slug: '',
  loanable: false,
})
const model = reactive<Partial<ItemType>>(defaultValue())

async function handleSubmit() {
  alert(Object.values(model))
}

async function close() {
  if (hasInput.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes, closing this will delete your progress. Are you sure you want to close this?' })

    if (!confirm.isConfirmed) {
      return
    }
    Object.assign(model, defaultValue())
  }
  nextTick(() => modal.value?.close())
}
</script>

<style scoped></style>
