<template>
  <Button variant="primary" left-icon="plus-lg" @click="modal?.open()">New Item Category</Button>

  <Modal ref="modal" size="large" :has-inputs="hasInput">
    <ModalHeader use-default-layout title="New Item Category" subtitle="Add a category to an item type" icon="collection" />
    <ModalBody>
      <Form id="new-item-category" class="flex flex-col gap-5 p-5" @submit="submit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Item Category Information</h1>

        <div class="grid grid-cols-2 gap-5">
          <Control id="item-category-name" col required>
            <Label>Name</Label>
            <Input type="text" placeholder="e.g. Reference" v-model="model.name" :error="errors?.name?.[0]" />
          </Control>
          <Control id="item-category-code" col required>
            <Label>Code</Label>
            <Input type="text" placeholder="e.g. REF" v-model="model.code" :error="errors?.code?.[0]" />
          </Control>
          <Control id="item-category-type" col required class="col-span-2">
            <Label>Item Type</Label>
            <Select id="item-category-type-select" v-model="model.item_type_id" placeholder="Select an item type" :error="errors?.item_type_id?.[0]">
              <Option value="" disabled>Select an item type</Option>
              <Option v-for="type in itemTypes.data" :key="type.id" :value="type.id">{{ type.name }}</Option>
            </Select>
          </Control>
        </div>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close()">Cancel</Button>
      <Button type="submit" variant="success" form="new-item-category">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const categories = useAdminItemCategoryStore()
const itemTypes = useAdminItemTypeStore()
const modal = ref<InstanceType<typeof Modal>>()
const errors = ref<Record<string, string[]> | null>(null)
const hasInput = computed(() => model.name !== '' || model.code !== '' || model.item_type_id !== '')

const defaultValue = (): Partial<ItemCategory> => ({
  name: '',
  code: '',
  item_type_id: '',
})
const model = reactive<Partial<ItemCategory>>(defaultValue())

async function submit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${model.name} to the item categories?`})
  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await categories.create(model)
    Object.assign(model, defaultValue())
    errors.value = null
    pop.unload()
    modal.value?.close()
    pop.success(res.message ?? 'Item category created successfully')

  } catch (e: any) {
    pop.unload()
    errors.value = e?.response?.data?.errors ?? null
    throw e
  }
}

onBeforeMount(async () => {
  await itemTypes.fetch()
})

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
