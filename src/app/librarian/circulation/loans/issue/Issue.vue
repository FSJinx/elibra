<template>
  <Card>
    <CardBody>
      <Form class="grid grid-cols-[2fr_2fr_1fr] items-end gap-3" id="issue-form" @submit="review">
        <Control id="patron-id" col required>
          <Label>Patron</Label>
          <Input ref="patronInput" v-model="form.patron" type="text" placeholder="Enter Patron's ID Number or EBC" auto-focus required />
        </Control>
        <Control id="item-accession" col required>
          <Label>Item</Label>
          <Input v-model="form.accession" type="text" placeholder="Enter item's accession code or barcode number" required />
        </Control>
        <div class="w-full grid grid-cols-2 items-center gap-2">
          <Button form="issue-form" type="submit" variant="primary" :loading="loading">
            {{ loading ? 'Checking...' : 'Check' }}
          </Button>
          <Button @click="clear">Clear</Button>
        </div>
      </Form>
    </CardBody>
  </Card>

  <Drawer ref="drawer" align="right" :loading="loading">
    <DrawerHeader class="flex items-center">
      <Icon icon="cart" class="mr-3" />
      Checkout
    </DrawerHeader>

    <DrawerBody class="flex flex-col gap-5">
      <!-- Patron Information -->
      <Card class="shadow">
        <CardHeader>
          <h5 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Patron's Information</h5>
        </CardHeader>
        <CardBody class="flex items-center gap-4">
          <img :src="user" alt="" class="size-20" />
          <div class="flex flex-col">
            <Title :level="2">{{ preview?.patron.name?.formal ?? 'Juan Dela Cruz' }}</Title>
            <p>{{ preview?.patron.username ?? '-' }}</p>
          </div>
        </CardBody>
        <CardFooter>
          <dl class="grid grid-cols-[1fr_2fr] gap-x-4 gap-y-2 text-sm">
            <dt class="text-muted-foreground">Patron Type</dt>
            <dd class="font-medium">{{ preview?.patron.patron?.patron_type?.name ?? '—' }}</dd>

            <dt class="text-muted-foreground">Department</dt>
            <dd class="font-medium capitalize">{{ preview?.patron.patron?.program?.department?.name ?? '—' }}</dd>

            <dt class="text-muted-foreground">Program</dt>
            <dd class="font-medium capitalize">{{ preview?.patron.patron?.program?.name ?? '—' }}</dd>

            <dt class="text-muted-foreground">EBC</dt>
            <dd class="font-mono font-medium">{{ preview?.patron.patron?.ebc_number ?? '—' }}</dd>
          </dl>
        </CardFooter>
      </Card>

      <!-- Item Information -->
      <Card class="shadow">
        <CardHeader>
          <h5 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Item's Information</h5>
        </CardHeader>

        <CardBody class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
          <div class="aspect-2/3 w-24 shrink-0 overflow-hidden rounded-xl border border-border shadow">
            <img :src="book" alt="" class="size-full object-cover" loading="lazy" />
          </div>

          <div class="min-w-0 text-center sm:text-left pt-2">
            <Title :level="2">{{ preview?.accession.item.title }}</Title>
            <p v-if="preview?.accession.item.description" class="text-sm text-muted-foreground line-clamp-5">
              {{ preview?.accession.item.description }}
            </p>
          </div>
        </CardBody>
        <CardFooter>
          <dl class="grid grid-cols-[1fr_1fr] items-center gap-x-4 gap-y-2 text-sm">
            <dt class="text-muted-foreground">Accession</dt>
            <dd class="font-mono font-medium">{{ preview?.accession.accession_number }}</dd>

            <dt class="text-muted-foreground">Call Number</dt>
            <dd class="font-medium">{{ preview?.accession.item.call_number ?? '—' }}</dd>

            <dt class="text-muted-foreground">Status</dt>
            <dd>
              <Status :variant="preview?.accession.status">{{ preview?.accession.status }}</Status>
            </dd>
          </dl>
        </CardFooter>
      </Card>

      <Form id="checkout-form" @submit="">
        <Control id="" col required>
          <Label>Loan Mode</Label>
          <Select v-model="loanModeId">
            <Option value="" selected disabled>Select loan mode</Option>
            <Option :value="item.id" v-for="item in options.loan_modes">{{ item.name }}</Option>
          </Select>
        </Control>
      </Form>
    </DrawerBody>

    <DrawerFooter class="grid grid-cols-2 gap-3" v-if="preview">
      <Button variant="danger" @click="cancel">Cancel</Button>
      <Button form="checkout-form" variant="success" type="submit">Proceed Loan</Button>
    </DrawerFooter> 
  </Drawer>
</template>

<script setup lang="ts">
import type Drawer from '@/components/drawer/Drawer.vue'
import book from '@/assets/images/default_book.png'
import user from '@/assets/images/user.png'
import Input from '@/components/form/Input.vue'

const loan = loanStore()
const pop = usePopup()
const options = optionStore()

const drawer = ref<InstanceType<typeof Drawer>>()
const loading = ref(false)
const errorMessage = ref(null)
const patronInput = ref<InstanceType<typeof Input>>()
const preview = ref<{ patron: User; accession: Accession } | null>(null)
const loanModeId = ref()
const emit = defineEmits(['refresh'])

const emptyForm = () => ({
  //   patron: '',
  //   accession: '',
  patron: '22-1513',
  accession: 'P000001',
})

const form = reactive(emptyForm())

async function review() {
  drawer.value?.open()
  loading.value = true
  try {
    const res = await loan.review(form)
    preview.value = res.data
  } catch (e: any) {
    errorMessage.value = e.response.data?.message
  } finally {
    loading.value = false
  }
}

function cancel() {
  clear()
  nextTick(() => drawer.value?.close())
}

function clear() {
  Object.assign(form, emptyForm())
  preview.value = null
  errorMessage.value = null
  loanModeId.value = ''

  nextTick()

  patronInput.value?.input?.focus()
}

async function checkout() {
  pop.load()
  try {
    const res = await loan.checkout({
      patron_id: preview.value?.patron?.patron?.user_id,
      accession_number: preview.value?.accession?.accession_number,
      loan_mode_id: loanModeId.value,
    })
    pop.success(res.message)
    emit('refresh')
    clear()
  } catch (e: any) {
    pop.error(e.response?.data?.message)
  }
}
</script>

<style scoped></style>
