<template>
  <Button size="sm" class="hover:text-warning" @click="open()">Edit</Button>

  <Modal ref="modal" size="large" :has-inputs="hasChanges">
    <ModalHeader use-default-layout title="Update Item Category" :subtitle="`Currently editing ${data.name}`" icon="collection" />
    <ModalBody>
      <Form :id="formId" class="flex flex-col gap-5 p-5" @submit="handleSubmit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Item Category Information</h1>
        <div class="grid grid-cols-2 gap-5">
          <Control :id="`item-category-name-${data.id}`" col required>
            <Label>Name</Label>
            <Input type="text" placeholder="e.g. Reference" v-model="model.name" :error="errors?.name?.[0]" />
          </Control>
          <Control :id="`item-category-code-${data.id}`" col required>
            <Label>Code</Label>
            <Input type="text" placeholder="e.g. REF" v-model="model.code" :error="errors?.code?.[0]" />
          </Control>
          <Control :id="`item-category-type-${data.id}`" col required class="col-span-2">
            <Label>Item Type</Label>
            <Select :id="`item-category-type-select-${data.id}`" v-model="model.item_type_id" placeholder="Select an item type" :error="errors?.item_type_id?.[0]">
              <Option value="" disabled>Select an item type</Option>
              <Option v-for="type in itemTypes.data" :key="type.id" :value="type.id">{{ type.name }}</Option>
            </Select>
          </Control>
        </div>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close()">Cancel</Button>
      <Button type="submit" variant="info" :form="formId" v-if="hasChanges">Update</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ data: ItemCategory }>()
const pop = usePopup()
const categories = useAdminItemCategoryStore()
const itemTypes = useAdminItemTypeStore()
const modal = ref<InstanceType<typeof Modal>>()
const errors = ref<Record<string, string[]> | null>(null)
const formId = computed(() => `update-item-category-${props.data.id}`)
const model = reactive<Partial<ItemCategory>>({ ...props.data })
const hasChanges = computed(() => JSON.stringify(model) !== JSON.stringify(props.data))

function open() {
  Object.assign(model, props.data)
  errors.value = null
  modal.value?.open()
}

async function handleSubmit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to update ${props.data.name}?` })
  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await categories.update({ ...props.data, ...model } as ItemCategory)
    Object.assign(model, res.data)
    errors.value = null
    pop.unload()
    await nextTick()
    modal.value?.close()
    pop.success(res.message ?? 'Item category updated successfully')
    nextTick(() => modal.value?.close())
  } catch (e: any) {
    pop.unload()
    errors.value = e?.response?.data?.errors ?? null
    throw e
  }
}

async function close() {
  if (hasChanges.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes. Are you sure you want to close this?' })
    if (!confirm.isConfirmed) return
    Object.assign(model, props.data)
    errors.value = null
  }
  nextTick(() => modal.value?.close())
}

onBeforeMount(async () => {
  await itemTypes.fetch()
})
</script>