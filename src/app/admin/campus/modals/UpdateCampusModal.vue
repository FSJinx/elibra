<template>
  <Button size="sm" variant="warning" @click="open()">Edit</Button>

  <Modal ref="modal" size="xlarge" :has-inputs="hasChanges">
    <ModalHeader use-default-layout :title="data.name" :subtitle="`Currently editing campus information of ${data.name}`" icon="buildings" />

    <ModalBody>
      <Form id="campus-update-form" class="flex flex-col p-5 gap-5" @submit="submit">
        <h1 class="uppercase tracking-wider font-medium text-sm text-muted-foreground">Update Campus Information</h1>

        <Control>
          <Label id="campus-name">Name</Label>
          <Input id="campus-name" v-model="form.name" placeholder="e.g. Echague Campus" :error="errors?.name?.[0]" />
        </Control>
        <Control required>
          <Label id="campus-code">Code</Label>
          <Input id="campus-code" v-model="form.code" placeholder="e.g. ISU-E" :error="errors?.code?.[0]" />
        </Control>
        <Control required>
          <Label id="campus-status">Status</Label>
          <Select id="campus-status" v-model="form.status" title="Status" placeholder="Select campus' status" :error="errors?.status?.[0]">
            <Option value="inactive">Inactive</Option>
            <Option value="active">Active</Option>
          </Select>
        </Control>
        <Control required>
          <Label id="campus-address">Address</Label>
          <Textarea id="campus-address" v-model="form.address" title="Status" placeholder="Select campus' status" :error="errors?.address?.[0]" />
        </Control>
      </Form>
    </ModalBody>

    <ModalFooter class="gap-1">
      <Button @click="close" variant="danger">Cancel</Button>
      <Button type="submit" variant="success" form="campus-update-form" v-if="hasChanges">Submit</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

interface Props {
  data: Campus
}

const props = defineProps<Props>()
const modal = ref<typeof Modal | null>(null)
const form = reactive({ ...props.data })
const errors = ref<Record<keyof Campus, string[]> | null>(null)
const pop = usePopup()
const hasChanges = computed(() => JSON.stringify(form) !== JSON.stringify(props.data))
const campus = useCampusStore()

async function submit() {
  const res = await pop.confirm({ text: 'Are you sure you want to keep this changes?' })

  if (res.isConfirmed) {
    errors.value = null
    try {
      pop.load()

      const res = await campus.update(form)
      pop.success(res.message ?? 'Something here.')
      nextTick(() => {
        reset()
        close()
      })
    } catch (e: any) {
      //   Object.assign(errors, e.response?.data?.errors)
      errors.value = e.response?.data?.errors
      console.log(e.response)

      throw e
    }
  }
}

async function close() {
  if (hasChanges.value) {
    const res = await pop.confirm({ text: 'Are you sure? You have unsaved changes, your progress will be lost' })

    if (!res.isConfirmed) {
      return
    }
    reset()
  }

  nextTick(() => modal.value?.close())
}

function reset() {
  errors.value = null
  Object.assign(form, props.data)
}

function open() {
  reset()
  modal.value?.open()
}
</script>

<style scoped></style>
