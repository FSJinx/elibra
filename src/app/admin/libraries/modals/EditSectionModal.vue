<template>
  <Button size="sm" class="hover:text-warning" @click="open">Edit</Button>

  <Modal ref="modal" size="normal" :has-inputs="hasChanges">
    <ModalHeader use-default-layout title="Edit Section" :subtitle="`Update ${data.name}`" icon="diagram-3" />
    <ModalBody>
      <Form :id="formId" class="flex flex-col gap-5 p-5" @submit="submit">
        <Control :id="`section-name-${data.id}`" col required>
          <Label>Name</Label>
          <Input v-model="form.name" placeholder="e.g. Reference" :error="errors?.name?.[0]" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button v-if="hasChanges" type="submit" variant="info" :form="formId">Update</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Form from '@/components/form/Form.vue'
import Modal from '@/components/my/Modal.vue'

const props = defineProps<{ data: Section }>()
const sections = sectionStore()
const pop = usePopup()
const modal = ref<InstanceType<typeof Modal>>()
const form = reactive({ name: props.data.name })
const errors = ref<Record<string, string[]> | null>(null)
const formId = computed(() => `update-section-${props.data.id}`)
const hasChanges = computed(() => form.name !== props.data.name)

function open() {
  form.name = props.data.name
  errors.value = null
  modal.value?.open()
}

async function submit() {
  form.name = form.name.trim()
  errors.value = null
  if (!form.name) {
    errors.value = { name: ['Section name is required'] }
    return
  }

  const confirmation = await pop.confirm({ text: `Are you sure you want to update ${props.data.name}?` })
  if (!confirmation.isConfirmed) return

  pop.load()
  try {
    const res = await sections.update({ ...props.data, name: form.name })
    pop.unload()
    form.name = res.data.name
    errors.value = null
    await nextTick()
    modal.value?.close()
    await pop.success(res.message ?? 'Section updated successfully')
  } catch (error: any) {
    pop.unload()
    errors.value = error?.response?.data?.errors ?? null
    if (!errors.value?.name) {
      await pop.error(error?.response?.data?.message ?? 'Unable to update the section. Please try again.')
    }
  }
}

async function close() {
  if (hasChanges.value) {
    const confirmation = await pop.confirm({ text: 'You have unsaved changes. Are you sure you want to close this?' })
    if (!confirmation.isConfirmed) return
  }

  form.name = props.data.name
  errors.value = null
  await nextTick()
  modal.value?.close()
}
</script>

<style scoped></style>
