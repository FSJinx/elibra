<template>
  <Button size="sm" class="hover:text-warning" @click="modal?.open()">Edit</Button>

  <Modal ref="modal" size="large" :has-inputs="hasChanges">
    <ModalHeader use-default-layout title="Update Item Type" :subtitle="`Currently editing ${data.name}`" icon="tags" />
    <ModalBody>
      <Form :id="formId" class="flex flex-col gap-5 p-5" @submit="submit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Item Type Information</h1>
        <div class="grid grid-cols-3 gap-5">
          <Control id="item-type-name" col required class="col-span-2">
            <Label>Name</Label>
            <Input type="text" placeholder="e.g. Book" v-model="model.name" :error="errors?.name?.[0]" />
          </Control>
          <Control id="item-type-code" col required>
            <Label>Code</Label>
            <Input type="text" placeholder="e.g. book" v-model="model.slug" :error="errors?.slug?.[0]" />
          </Control>
          <Checkbox id="item-type-loanable" label="Available for loans" v-model="model.loanable" />
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

const props = defineProps<{ data: ItemType }>()
const pop = usePopup()
const item_type = useAdminItemTypeStore()
const modal = ref<InstanceType<typeof Modal>>()
const errors = ref<Record<string, string[]> | null>(null)
const formId = computed(() => `update-item-type-${props.data.id}`)
const model = reactive<Partial<ItemType>>({ ...props.data })
const hasChanges = computed(() => JSON.stringify(model) !== JSON.stringify(props.data))

async function submit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to update ${props.data.name}?` })
  if (!confirm.isConfirmed) return

  pop.load()
    try {
    const res = await item_type.update({ ...props.data, ...model } as ItemType)
    Object.assign(model, res.data)
    errors.value = null
    pop.success(res.message ?? 'Item type updated successfully')
    nextTick(() => modal.value?.close())
    } 
    catch (e: any) {
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
</script>

the updateitemtype do not auto close when update was clicked, success animation, data updated