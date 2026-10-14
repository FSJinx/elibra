<template>
  <Button variant="primary" left-icon="plus-lg" @click="open">New Librarian</Button>

  <Modal ref="modal" size="large" :has-inputs="hasInput">
    <ModalHeader use-default-layout title="New Librarian" subtitle="Create a new library staff for this library" icon="person-square" />

    <ModalBody>
      <Form id="create-librarian" @submit="submit" class="grid grid-cols-4 gap-3 p-5">
        <Control id="librarian-first-name" class="col-span-4" col required>
          <Label>First Name</Label>
          <Input type="text" placeholder="e.g. Juan" v-model="form.first_name" />
        </Control>
        <Control id="librarian-last-name" class="col-span-3" col required>
          <Label>Last Name</Label>
          <Input type="text" placeholder="e.g. Dela Cruz" v-model="form.last_name" />
        </Control>
        <Control id="librarian-middle-initial" col>
          <Label>Middle Initial</Label>
          <Input type="text" placeholder="e.g. Dela Cruz" max="2" v-model="form.middle_initial" />
        </Control>

        <Control id="librarian-email" class="col-span-3" col>
          <Label>Email</Label>
          <Input type="email" placeholder="e.g. example@isu.edu.ph" v-model="form.email" />
        </Control>

        <Control id="librarian-role" col required>
          <Label>Sex</Label>
          <Select title="Sex" v-model="form.sex">
            <Option value="" selected disabled>Select sex</Option>
            <Option value="male">Male</Option>
            <Option value="female">Female</Option>
          </Select>
        </Control>
      </Form>
    </ModalBody>

    <ModalFooter>
      <Button form="create-librarian" type="submit" variant="success">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

const modal = ref<InstanceType<typeof Modal>>()

const emptyForm = () => ({ last_name: '', first_name: '', middle_initial: '', username: '', sex: '', role: '', email: '', section: '' })
const form = reactive(emptyForm())
const hasInput = computed(() => Object.values(form).some((i) => i.length > 0))

function open() {
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {}
</script>

<style scoped></style>
