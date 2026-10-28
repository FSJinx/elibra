<template>
  <Button variant="primary" left-icon="plus-lg" @click="open">Add Patron</Button>

  <Modal ref="modal" size="large">
    <ModalHeader use-default-layout title="Patron Type Loan Policy" subtitle="Setup rules for patron when loaning through this method" icon="person" />
    <ModalBody class="p-5">
      <Form id="loan-policy-form" class="space-y-4" @submit="submit">
        <Control id="policy-patron-type" col required>
          <Label>Patron Type</Label>
          <Select id="policy-patron-type" v-model="form.patron_type_id" placeholder="Select patron type" :error="errors?.patron_type_id?.[0]">
            <Option value="" selected   disabled>Select patron type</Option>
            <Option v-for="type in missingPatronTypes" :key="type.id" :value="type.id">{{ type.name }}</Option>
          </Select>
          <p v-if="selectedPatronTypeName" class="text-sm text-muted-foreground">
            Policy will apply to {{ selectedPatronTypeName }} patrons.
          </p>
        </Control>
        <Control id="policy-loan-period" col required>
          <Label>Loan Period <span class="text-sm text-muted-foreground font-normal">(in days)</span></Label>
          <div class="flex items-center gap-3">
            <Input id="policy-loan-period" v-model="form.loan_period_days" type="number" placeholder="Enter loan period" :error="errors?.loan_period_days?.[0]" />
            <Select v-model="form.loan_period_unit">
              <Option value="" selected disabled>Select loan period unit</Option>
              <Option value="hour">Hour</Option>
              <Option value="day">Day</Option>
              <Option value="week">Week</Option>
            </Select>
          </div>
        </Control>
        <Control id="policy-max-items" col required>
          <Label>Max Loanable Items</Label>
          <Input id="policy-max-items" v-model="form.max_items" type="number" min="0" placeholder="Enter maximum loanable items" :error="errors?.max_items?.[0]" />
        </Control>
        <Control id="policy-max-renewals" col required>
          <Label>Max Renewals</Label>
          <Input id="policy-max-renewals" v-model="form.max_renewals" type="number" min="0" placeholder="Enter maximum renewals" :error="errors?.max_renewals?.[0]" />
        </Control>
        <Control id="policy-reservation-limit" col required>
          <Label>Reservation Limit</Label>
          <Input id="policy-reservation-limit" v-model="form.reservation_limit" type="number" min="0" placeholder="Enter reservation limit" :disabled="!form.can_reserve" :error="errors?.reservation_limit?.[0]" />
        </Control>
        <Control id="policy-fine-per-due" col required>
          <Label>Fine Per Due</Label>
          <Input id="policy-fine-per-due" v-model="form.fine_per_due" type="number" min="0" step="0.01" placeholder="Enter fine per due" :error="errors?.fine_per_due?.[0]" />
        </Control>
        <Control id="policy-can-reserve" col required>
          <Label>Can Reserve</Label>
          <Select id="policy-can-reserve" v-model="form.can_reserve" placeholder="Select if patron can reserve" :error="errors?.can_reserve?.[0]">
            <Option value="" selected disabled>Select option</Option>
            <Option :value="true">Yes</Option>
            <Option :value="false">No</Option>
          </Select>
        </Control>
        <Control id="policy-grace-period" col required>
          <Label>Grace Period <span class="text-sm text-muted-foreground font-normal">(in days)</span></Label>
          <div class="flex items-center gap-3">
            <Input id="policy-grace-period" v-model="form.grace_period" type="number" placeholder="Enter grace period" :error="errors?.grace_period?.[0]" />
            <Select v-model="form.grace_period_unit">
              <Option value="" selected disabled>Select grace period unit</Option>
              <Option value="hour">Hour</Option>
              <Option value="day">Day</Option>
              <Option value="week">Week</Option>
            </Select>
          </div>
        </Control>
        <Control id="policy-notes" col nullable>
          <Label>Notes</Label>
          <Textarea id="policy-notes" v-model="form.notes" placeholder="Add notes about this loan policy" :error="errors?.notes?.[0]" />
        </Control>
      </Form>
    </ModalBody>
    <ModalFooter class="space-x-2">
      <Button variant="danger" @click="close">Cancel</Button>
      <Button form="loan-policy-form" variant="success" type="submit">Create</Button>
    </ModalFooter>
  </Modal>
</template>

<script setup lang="ts">
import Modal from '@/components/my/Modal.vue'
import type { PatronType } from '@/interfaces/patron_type'

const policy = patronTypeLoanPolicyStore()  
const loan_mode = loanModeStore()
const options = optionStore()
const pop = usePopup()

const emit = defineEmits(['refresh'])
const modal = ref<InstanceType<typeof Modal>>()
const missingPatronTypes = computed(() => options.patron_types?.filter((t: Partial<PatronType>) => !policy.data?.some((p: any) => p.patron_type_id === t.id)) ?? [])
const noNewPatronType = computed(() => !missingPatronTypes.value.length)

const defaultForm = (): StorePatronTypeLoanPolicy => ({
  patron_type_id: '',
  loan_period_days: '',
  max_items: 1,
  max_renewals: 0,
  can_reserve: false,
  reservation_limit: 0,
  fine_per_due: 0,
  grace_period: 0,
  notes: '',
  loan_mode_id: loan_mode.currentData?.id ?? null,
})  
const form = reactive<StorePatronTypeLoanPolicy>(defaultForm())
const selectedPatronTypeName = computed(() =>
  missingPatronTypes.value.find((type) => String(type.id) === String(form.patron_type_id))?.name,
)
const errors = ref<Record<string, string[]> | null>(null)

function open() {
  if (!options.patron_types?.length) return pop.info('There are no patron types yet. Create one now and start setting up loan policy for each patron type.')
  if (noNewPatronType.value) return pop.info('All patron types already have their own policy for this loan mode. Edit them instead.')

  Object.assign(form, defaultForm())
  errors.value = null
  modal.value?.open()
}

function close() {
  modal.value?.close()
}

async function submit() {
  const confirm = await pop.confirm({ text: 'Confirm adding this patron loan policy' })
  if (!confirm.isConfirmed) return

  pop.load()
  try {
    const res = await policy.create(form)
    Object.assign(form, defaultForm())
    errors.value = null
    close()
    emit('refresh')
    pop.success(res.message ?? 'Patron loan policy created successfully')
  } catch (e: any) {
    errors.value = e?.response?.data?.errors ?? null
  } finally {
    pop.unload()
  }
}

onBeforeMount(() => options.fetch('patron_types'))
</script>

<style scoped></style>
