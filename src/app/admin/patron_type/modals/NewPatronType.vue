<template>
  <Button variant="primary" left-icon="plus-lg" @click="modal?.open()">New Patron Type</Button>

  <Modal ref="modal" :has-inputs="hasInput">
    <ModalHeader use-default-layout title="New Patron Type" subtitle="Add a new patron type to the list" icon="people" />
    <ModalBody>
      <Form id="new-patron-type" class="flex flex-col gap-3 p-5" @submit="handleSubmit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Patron Type Information</h1>

        <Control id="patron-type-name" col required class="col-span-3">
          <Label>Name</Label>
          <Input type="text" placeholder="e.g. Student" v-model="model.name" :error="errors?.name?.[0]" />
        </Control>
        <Control id="patron-type-key" col required>
          <Label>Code</Label>
          <Input type="text" placeholder="e.g. student" v-model="model.key" :error="errors?.key?.[0]" />
        </Control>
        <Control class="col-span-2" id="patron-type-validity-period" col required>
          <Label>Account Validity Period</Label>
          <Input type="number" min="1" v-model="model.validity_period" :error="errors?.validity_period?.[0]" helper="In years"/>
        </Control>

        <Control id="patron-type-description" col nullable class="col-span-3">
          <Label>Description</Label>
          <Textarea v-model="model.description" placeholder="Describe this patron type" :error="errors?.description?.[0]" />
        </Control>
        <div class="col-span-3 flex flex-col gap-5 pb-2">
          <Checkbox id="patron-type-has-campus" label="Has campus" v-model="model.has_campus" />
          <Checkbox id="patron-type-has-department" label="Has department" v-model="model.has_department" />
          <Checkbox id="patron-type-has-program" label="Has program" v-model="model.has_program" />
        </div>
      </Form>
    </ModalBody>
    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close()">Cancel</Button>
      <Button type="submit" variant="success" form="new-patron-type">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const type = patronTypeStore()
const emit = defineEmits(['refresh'])
const modal = ref<InstanceType<typeof Modal>>()
const errors = ref<Record<string, string[]> | null>(null)
const hasInput = computed(() => model.name !== '' || model.key !== '' || model.description !== '' || model.validity_period !== 1)

const defaultValue = (): Partial<StorePatronType> => ({
  key: '',
  name: '',
  description: '',
  validity_period: 1,
  has_campus: false,
  has_department: false,
  has_program: false,
})
const model = reactive<Partial<StorePatronType>>(defaultValue())

async function handleSubmit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${model.name} to the patron types?` })
  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await type.create(model)
    Object.assign(model, defaultValue())
    errors.value = null
    nextTick(() => modal.value?.close())
    emit('refresh')
    pop.success(res.message ?? 'Patron type created successfully')
  } catch (error: any) {
    errors.value = error?.response?.data?.errors ?? null
  } finally {
    pop.unload()
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
