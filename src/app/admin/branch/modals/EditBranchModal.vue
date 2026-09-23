<template>
  <Button size="sm" variant="warning" @click="open()">Edit</Button>

  <Modal ref="modal" size="xlarge" :has-inputs="hasChanges">
    <ModalHeader use-default-layout :title="data.name" :subtitle="`Currently editing branch information of ${data.name}`" icon="building" />

    <ModalBody>
      <Form id="branch-update-form" class="grid grid-cols-2 p-5 gap-5" @submit="submit">
        <h1 class="col-span-2 uppercase tracking-wider font-medium text-sm text-muted-foreground">Branch Information</h1>

        <Control col required class="col-span-2">
          <Label id="branch-name">Name</Label>
          <Input id="branch-name" v-model="form.name" placeholder="e.g. University Branch" :error="errors?.name?.[0]" />
        </Control>
        <Control col>
          <Label id="branch-contact_info">Contact Info</Label>
          <Input id="branch-contact_info" type="tel" v-model="form.contact_info" placeholder="e.g. 09xxxxxxxxx" :error="errors?.contact_info?.[0]" />
        </Control>
        <Control col required>
          <Label id="branch-email">Email</Label>
          <Input id="branch-email" type="email" v-model="form.email" placeholder="e.g. isu-e.library@isu.edu.ph" :error="errors?.email?.[0]" />
        </Control>
        <Control col required class="col-span-2">
          <Label id="branch-campus">Campus</Label>
          <Select id="branch-campus" v-model="form.campus_id" placeholder="e.g. University Branch" :error="errors?.campus_id?.[0]">
            <Option value="" disabled>Select Campus</Option>
            <Option :value="c.id" v-for="c in campus.data">{{ c.name }}</Option>
          </Select>
        </Control>

        <h1 class="col-span-2 uppercase tracking-wider font-medium text-sm text-muted-foreground">Branch Operation Hours</h1>
        
        <Control col required>
          <Label id="branch-opening_hour">Opening Hour</Label>
          <TimePicker id="branch-opening_hour" v-model="form.opening_hour" :error="errors?.opening_hour?.[0]" />
        </Control>
        <Control col required>
          <Label id="branch-closing_hour">Closing Hour</Label>
          <TimePicker id="branch-closing_hour" v-model="form.closing_hour" :error="errors?.closing_hour?.[0]" />
        </Control>
      </Form>
    </ModalBody>

    <ModalFooter class="gap-2">
      <Button @click="close" variant="danger">Cancel</Button>
      <Button type="submit" variant="success" form="branch-update-form" v-if="hasChanges">Submit</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'

interface Props {
  data: Branch
}

const props = defineProps<Props>()
const modal = ref<typeof Modal | null>(null)
const form = reactive({ ...props.data })
const errors = ref<Record<keyof Branch, string[]> | null>(null)
const pop = usePopup()
const hasChanges = computed(() => JSON.stringify(form) !== JSON.stringify(props.data))
const branch = useBranchStore()
const campus = useCampusStore()

async function submit() {
  const res = await pop.confirm({ text: 'Are you sure you want to keep this changes?' })

  if (res.isConfirmed) {
    errors.value = null
    try {
      pop.load()

      const res = await branch.update(form)
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
