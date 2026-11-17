<template>
  <Button size="sm" variant="info" @click="open"> Verify </Button>

  <Modal ref="modal">
    <ModalHeader use-default-layout :title="data.accession?.item?.title ?? 'Untitled'" subtitle="Verify this item's status" icon="" />
    <ModalBody class="p-5">
      <p class="uppercase font-semibold tracking-wide text-sm text-foreground-secondary mb-5">Verify Status</p>
      <Form class="flex flex-col gap-2" id="update-inventory-line" @submit="submit">
        <Control id="line-title" col>
          <Label>Title</Label>
          <Input type="text" placeholder="" :model-value="data.accession?.item?.title" readonly />
        </Control>

        <Control id="line-status" required col>
          <Label>Status</Label>
          <Select v-model="form.new_status" title="" placeholder="Current Status">
            <Option value="available" label="Available" />
            <Option value="reserved" label="Reserved" />
            <Option value="borrowed" label="Borrowed" />
            <Option value="borrowed" label="Borrowed" />
            <Option value="lost" label="Lost" />
            <Option value="missing" label="Missing" />
            <Option value="archived" label="Archived" />
            <Option value="condemned" label="Condemned" />
          </Select>
        </Control>

        <Control id="line-status" required col>
          <Label>Shelf Location</Label>
          <Select v-model="form.new_section_id" title="Sections">
            <Option value="" label="Unshelved"></Option>
            <Option v-for="item in options.sections?.filter((i) => i.library_id === auth.user?.library?.id)" :value="item.id" :label="item.name"></Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="justify-end gap-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="update-inventory-line" variant="success" type="submit">Verify</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ data: InventoryLine }>()

const options = optionStore()
const pop = usePopup()
const line = inventoryLineStore()
const auth = authStore()

const emit = defineEmits(['refresh'])
const modal = ref<InstanceType<typeof Modal>>()
const form = reactive<UpdateInventoryLine>({})

function open() {
  Object.assign(form, {
    id: props.data.id,
    new_status: props.data.new_status ?? props.data.current_status,
    verifier_id: auth.user?.librarian?.id ?? null,
  })
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  pop.load()
  try {
    const res = await line.verify(form)
    pop.success(res.message)
    emit('refresh')
    close()
  } catch (e: any) {
    pop.error(e.response?.data?.message ?? 'Something went wrong, please try again')
  }
}
</script>

<style scoped></style>
