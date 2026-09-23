<template>
  <Button variant="primary" icon="plus-lg" @click="modal?.open()">Add New</Button>
  <Modal ref="modal" size="large" :has-inputs="hasInputs">
    <ModalHeader use-default-layout title="New Campus" subtitle="Add new ISU campus to the record" icon="building-fill-add" />

    <ModalBody>
      <Form id="campus-form" class="flex flex-col p-5 gap-5" @submit="submit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Campus Information</h1>
        <div class="grid grid-cols-2 gap-5">
          <Control col required>
            <Label id="campus-name">Name</Label>
            <Input id="campus-name" placeholder="e.g. Echague Campus" v-model="form.name" :error="errors?.name?.[0]" />
          </Control>
          <Control col required>
            <Label id="campus-code">Code</Label>
            <Input id="campus-code" placeholder="e.g. ISU-E" v-model="form.code" :error="errors?.code?.[0]" />
          </Control>
          <Control col class="col-span-2" required>
            <Label id="campus-address">Address</Label>
            <Input id="campus-address" placeholder="e.g. San Fabian, Echague, Isabela" helper="Please enter campus full address" v-model="form.address" :error="errors?.address?.[0]" />
          </Control>
        </div>
      </Form>
    </ModalBody>

    <ModalFooter class="gap-2">
      <Button variant="danger" @click="close()">Cancel</Button>
      <Button variant="success" type="submit" form="campus-form">Create Campus</Button>
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
const errors = ref<Campus | null>(null)
const hasInputs = computed(() => Object.values(form).some((value) => value != null && String(value).length > 0))

async function close() {
  if (hasInputs.value) {
    const confirm = await pop.confirm({ text: 'You have unsaved changes, do you really want to cancel adding?' })
    if (!confirm.isConfirmed) return

    Object.assign(form, defaultCampus())
  }

  nextTick(() => modal.value?.close())
}

async function submit() {
  const confirm = await pop.confirm({ text: `Are you sure you want to add ${form?.name} to the record?` })
  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await campus.create(form)

      Object.assign(form, defaultCampus())
      nextTick(() => modal?.value?.close())
      pop.success(res?.message)
    } catch (e: any) {
      const messages = e?.response?.data?.errors
      errors.value = messages
      console.log(messages)

      throw e
    }
  }
}
</script>

<style scoped></style>
