<template>
  <Button size="sm" class="hover:text-info" @click="open">Edit</Button>

  <Modal ref="modal" :has-inputs="hasChanges">
    <ModalHeader use-default-layout title="Edit Section" :subtitle="`Currently editing ${data?.name}`" icon="building-plus" />
    <ModalBody class="p-5">
      <p class="uppercase tracking-wide text-muted-foreground font-medium text-sm mb-3">Update Information</p>
      <Form id="edit-section" @submit="submit">
        <Control id="section-name" col required>
          <Label>Name</Label>
          <Input type="text" placeholder="e.g. General Section" v-model="form.name" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="edit-section" variant="success" type="submit" :disabled="!hasChanges">Update</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const section = sectionStore()

const props = defineProps<{ data: Section | null }>()
const modal = ref<InstanceType<typeof Modal>>()

const form = reactive<Section>(structuredClone(toRaw(props.data)) as Section)

const hasChanges = computed(() => JSON.stringify(form) !== JSON.stringify(props.data))

function reset() {
  Object.assign(form, structuredClone(toRaw(props.data)) as Section)
}

function open() {
  modal.value?.open()
}

async function close() {
  if (hasChanges.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes, are you you want to cancel editing?' })

    if (!confirm.isConfirmed) {
      return
    }
    reset()
  }

  nextTick(() => modal.value?.close())
}

async function submit() {
  pop.load()
  try {
    const res = await section.update(props.data?.id, form)
    pop.success(res.message)
    reset()
    nextTick(close)
  } catch (e: any) {
    throw e
  }
}
</script>

<style scoped></style>
