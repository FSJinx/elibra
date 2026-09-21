<template>
  <Button variant="primary" icon="plus-lg" @click="modal?.open()">Add New</Button>
  <Modal ref="modal" size="large" :has-inputs="hasInputs">
    <ModalHeader use-default-layout title="New Campus" subtitle="Add new ISU campus to the record" icon="building-fill-add" />

    <ModalBody>
      <Form id="campus-form" class="flex flex-col p-5 gap-5" @submit="submit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Campus Information</h1>
        <Control required>
          <Label id="campus-name">Name</Label>
          <Input id="campus-name" placeholder="e.g. Echague Campus" v-model="form.name" />
        </Control>
        <Control required>
          <Label id="campus-code">Code</Label>
          <Input id="campus-code" placeholder="e.g. ISU-E" v-model="form.code" />
        </Control>
        <Control required>
          <Label id="campus-address">Address</Label>
          <Textarea id="campus-address" placeholder="e.g. San Fabian, Echague, Isabela" helper="Please enter campus full address" v-model="form.address" />
        </Control>
      </Form>
    </ModalBody>

    <ModalFooter>
      <Button variant="primary" type="submit" form="campus-form">Create Campus</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Form from '@/components/form/Form.vue'
import Modal from '@/components/my/Modal.vue'

const defaultCampus = (): Partial<Campus> => ({
  name: '',
  code: '',
  address: '',
})

const modal = ref<typeof Modal | null>(null)
const campus = useCampusStore()
const pop = usePopup()
const form = reactive(defaultCampus())
const hasInputs = computed(() => Object.values(form).some((value) => value != null && String(value).length > 0))

async function submit() {
  const res = await campus.create(form)

  if (res?.status === 'success') {
    Object.assign(form, defaultCampus())
    nextTick(() => modal?.value?.close())
  }
}
</script>

<style scoped></style>
