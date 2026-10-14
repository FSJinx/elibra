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
            <Input type="text" placeholder="e.g. Book" v-model="model.name" :error="errors?.name?.[0]" />
          </Control>
          <Control id="item_type-code" col required>
            <Label>Code</Label>
            <Input type="text" placeholder="e.g. Book" v-model="model.slug" :error="errors?.slug?.[0]" />
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
const item_type = useAdminItemTypeStore()
const modal = ref<InstanceType<typeof Modal>>()
const errors = ref<Record<string, string[]> | null>(null)
const hasInput = computed(() => model.name !== '' || model.slug !== '')

const defaultValue = (): Partial<ItemType> => ({
  name: '',
  slug: '',
  loanable: false,
})
const model = reactive<Partial<ItemType>>(defaultValue())

async function handleSubmit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${model.name} to the item types?` })
  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await item_type.create(model)
    Object.assign(model, defaultValue())
    errors.value = null
    nextTick(() => modal.value?.close())
    pop.success(res.message ?? 'Item type created successfully')
  } catch (error: any) {
    pop.unload()
    errors.value = error?.response?.data?.errors ?? null
  }
}

async function close() {
  if (hasInput.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes, closing this will delete your progress. Are you sure you want to close this?' })

    if (!confirm.isConfirmed) {
      return
    }
    Object.assign(model, defaultValue())
    errors.value = null
  }
  nextTick(() => modal.value?.close())
}
</script>

<style scoped></style>
