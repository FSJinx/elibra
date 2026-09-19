<template>
  <Button variant="primary" icon="plus-lg" @click="modal?.open()">Add New</Button>
  <Modal ref="modal" size="large" :has-inputs="hasInputs">
    <ModalHeader use-default-layout title="New Branch" subtitle="Add new library branch to the record" icon="building-fill-add" />

    <ModalBody>
      <Form id="branch-form" class="flex flex-col p-5 gap-5" @submit="submit">
        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Branch Information</h1>

        <div class="grid grid-cols-2 gap-5">
          <Control col required class="col-span-2">
            <Label id="branch-name">Name</Label>
            <Input id="branch-name" placeholder="e.g. University Library" v-model="form.name" />
          </Control>
          <Control col>
            <Label id="branch-contact">Contact Info</Label>
            <Input id="branch-contact" type="tel" pattern="[0-9]{9}" placeholder="e.g. 09XXXXXXXXX" v-model="form.contact_info" />
          </Control>
          <Control col required>
            <Label id="branch-email">Email</Label>
            <Input id="branch-email" placeholder="e.g. example@email.com" v-model="form.email" />
          </Control>
          <Control col required class="col-span-2">
            <Label id="branch-campus">Campus</Label>
            <Select id="branch-campus" title="Campuses" v-model="form.campus_id">
              <Option value="" disabled>Select a campus where this branch is located</Option>
              <Option :value="c.id" v-for="c in campus.data">{{ c?.name }} ({{ c?.code }})</Option>
            </Select>
          </Control>
        </div>

        <h1 class="text-sm uppercase tracking-wider text-muted-foreground font-medium">Branch Operation Hours</h1>
        <div class="grid grid-cols-2 gap-5">
          <Control col required>
            <Label id="branch-opening_hour">Opening Hours</Label>
            <input type="time" v-model="form.opening_hour" />
          </Control>
          <Control col required>
            <Label id="branch-closing_hour">Closing Hours</Label>
            <input type="time" v-model="form.closing_hour" />
          </Control>
        </div>
      </Form>
    </ModalBody>

    <ModalFooter>
      <Button variant="primary" type="submit" form="branch-form">Create Branch</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Form from '@/components/form/Form.vue'
import Modal from '@/components/my/Modal.vue'

const defaultBranch = (): Partial<Branch> => ({
  name: '',
  code: '',
  address: '',
})

const modal = ref<typeof Modal | null>(null)
const branch = useBranchStore()
const campus = useCampusStore()
const pop = usePopup()
const form = reactive(defaultBranch())
const hasInputs = computed(() => Object.values(form).some((value) => value != null && String(value).length > 0))

async function submit() {
  const res = await branch.create(form)

  if (res?.status === 'success') {
    Object.assign(form, defaultBranch())
    nextTick(() => modal?.value?.close())
  }
}
</script>

<style scoped></style>
