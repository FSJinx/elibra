<template>
  <Button class="hover:text-info" size="sm" @click="open">Edit</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Edit Loan Policy" subtitle="Edit the behavior of how this patron can loan in this loan mode" icon="cash-coin" />

    <ModalBody class="p-5">
      <Form id="update-loan-policy" class="space-y-4" @submit="submit">
        <Control id="policy-patron-type" col>
          <Label>Patron Type</Label>
          <Input :model-value="props.data.patron_type?.name ?? 'Unknown patron type'" readonly />
        </Control>
        <Control id="policy-can-reserve" col>
          <Label>Can Reserve</Label>
          <Select v-model="form.can_reserve" placeholder="Select if patron can reserve" :error="errors?.can_reserve?.[0]">
            <Option :value="true">Yes</Option>
            <Option :value="false">No</Option>
          </Select>
        </Control>
        <Control id="policy-reservation-limit" col required>
          <Label>Reservation Limit</Label>
          <Input id="policy-reservation-limit" v-model="form.reservation_limit" type="number" placeholder="Enter reservation limit" :error="errors?.reservation_limit?.[0]" />
        </Control>
        <Control id="policy-loan-period" col required>
          <Label>Loan Period <span class="text-sm text-muted-foreground font-normal">(in days)</span></Label>
          <Input id="policy-loan-period" v-model="form.loan_period_days" type="number" placeholder="Enter loan period" :error="errors?.loan_period_days?.[0]" />
        </Control>
        <Control id="policy-max-items" col required>
          <Label>Max Loanable Items</Label>
          <Input id="policy-max-items" v-model="form.max_items" type="number" placeholder="Enter maximum loanable items" :error="errors?.max_items?.[0]" />
        </Control>
        <Control id="policy-max-renewals" col required>
          <Label>Max Renewals</Label>
          <Input id="policy-max-renewals" v-model="form.max_renewals" type="number" placeholder="Enter maximum renewals" :error="errors?.max_renewals?.[0]" />
        </Control>
        <Control id="policy-fine-per-due" col required>
          <Label>Fine Per Due</Label>
          <Input id="policy-fine-per-due" v-model="form.fine_per_due" type="number" step="0.01" placeholder="Enter fine per due" :error="errors?.fine_per_due?.[0]" />
        </Control>
        <Control id="policy-grace-period" col required>
          <Label>Grace Period <span class="text-sm text-muted-foreground font-normal">(in days)</span></Label>
          <Input id="policy-grace-period" v-model="form.grace_period" type="number" placeholder="Enter grace period" :error="errors?.grace_period?.[0]" />
        </Control>
        <Control id="policy-notes" col nullable>
          <Label>Notes</Label>
          <Textarea id="policy-notes" v-model="form.notes" placeholder="Add notes about this loan policy" :error="errors?.notes?.[0]" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="update-loan-policy" variant="success" type="submit">Save</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import type Modal from '@/components/my/Modal.vue'

const pop = usePopup()
const policy = patronTypeLoanPolicyStore()

const emit = defineEmits(['refresh'])
const props = defineProps<{ data: PatronTypeLoanPolicy }>()

const modal = ref<InstanceType<typeof Modal>>()
const defaultForm = ref<PatronTypeLoanPolicy>(structuredClone(toRaw(props.data)))
const form = reactive<UpdatePatronTypeLoanPolicy>(defaultForm.value)
const errors = ref<Record<string, string[]> | null>(null)

function open() {
  Object.assign(form, toRaw(props.data))
  errors.value = null
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

function reset() {
  Object.assign(form, defaultForm.value)
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Confirm update to this loan policy' })
  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await policy.update(form)
      close()
      emit('refresh')
      pop.success(res.message ?? 'Patron loan policy updated successfully')
    } catch (e: any) {
      pop.unload()
      errors.value = e?.response?.data?.errors ?? null
      if (!errors.value) {
        pop.error(e?.response?.data?.message ?? 'Failed to update patron loan policy')
      }
    }
  }
}
</script>

<style scoped></style>
