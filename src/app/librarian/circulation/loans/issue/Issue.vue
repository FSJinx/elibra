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

  <Card class="flex-1">
    <CardHeader>
      <Title :level="4">Loan Preview</Title>
      <p class="text-muted-foreground text-sm">Review the loan before proceeding to checkout.</p>
    </CardHeader>

    <CardBody v-if="loading" class="place-content-center">
      <Spinner class="m-auto" />
    </CardBody>

    <CardBody v-else-if="!preview" class="place-content-center">
      <div class="text-center p-10 m-auto">{{ errorMessage ?? 'No item on the preview.' }}</div>
    </CardBody>

    <CardBody v-else>
      <div class="grid gap-5 lg:grid-cols-2">
        <!-- Patron -->
        <Card class="">
          <CardHeader>
            <h5 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Patron's Information</h5>
          </CardHeader>

          <CardBody class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
            <div class="size-24 shrink-0 overflow-hidden rounded-full border border-border shadow">
              <img :src="user" alt="" class="size-full object-cover" loading="lazy" />
            </div>

            <div class="min-w-0 text-center sm:text-left pt-2">
              <Title :level="2" class="truncate">
                {{ preview.patron.first_name }}
                {{ preview.patron.middle_initial ? preview.patron.middle_initial + '.' : '' }}
                {{ preview.patron.last_name }}
              </Title>
              <p class="text-sm text-muted-foreground">{{ preview.patron.username }}</p>
            </div>
          </CardBody>
          <CardFooter>
            <dl class="grid grid-cols-[1fr_2fr] gap-x-4 gap-y-2 text-sm">
              <dt class="text-muted-foreground">Patron Type</dt>
              <dd class="font-medium">{{ preview.patron.patron?.patron_type?.name ?? '—' }}</dd>

              <dt class="text-muted-foreground">Department</dt>
              <dd class="font-medium capitalize">{{ preview.patron.patron?.program?.department?.name ?? '—' }}</dd>

              <dt class="text-muted-foreground">Program</dt>
              <dd class="font-medium capitalize">{{ preview.patron.patron?.program?.name ?? '—' }}</dd>

              <dt class="text-muted-foreground">EBC</dt>
              <dd class="font-mono font-medium">{{ preview.patron.patron?.ebc_number ?? '—' }}</dd>
            </dl>
          </CardFooter>
        </Card>

        <!-- Item -->
        <Card class="">
          <CardHeader>
            <h5 class="text-sm font-semibold uppercase tracking-wider text-muted-foreground">Item's Information</h5>
          </CardHeader>

          <CardBody class="flex flex-col items-center gap-4 sm:flex-row sm:items-start">
            <div class="aspect-2/3 w-24 shrink-0 overflow-hidden rounded-xl border border-border shadow">
              <img :src="book" alt="" class="size-full object-cover" loading="lazy" />
            </div>

            <div class="min-w-0 text-center sm:text-left pt-2">
              <Title :level="2">{{ preview.accession.item.title }}</Title>
              <p v-if="preview.accession.item.description" class="text-sm text-muted-foreground line-clamp-5">
                {{ preview.accession.item.subtitle }}
              </p>
            </div>
          </CardBody>
          <CardFooter>
            <dl class="grid grid-cols-[1fr_1fr] items-center gap-x-4 gap-y-2 text-sm">
              <dt class="text-muted-foreground">Accession</dt>
              <dd class="font-mono font-medium">{{ preview.accession.accession_number }}</dd>

              <dt class="text-muted-foreground">Call Number</dt>
              <dd class="font-medium">{{ preview.accession.item.call_number ?? '—' }}</dd>

              <dt class="text-muted-foreground">Status</dt>
              <dd>
                <Status :variant="preview.accession.status">{{ preview.accession.status }}</Status>
              </dd>
            </dl>
          </CardFooter>
        </Card>
      </div>
    </CardBody>
    <CardFooter v-if="preview">
      <Button>Proceed Loan</Button>
    </CardFooter>
  </Card>
</template>

<script setup lang="ts">
import book from '@/assets/images/default_book.png'
import user from '@/assets/images/user.png'
import Input from '@/components/form/Input.vue'

const loan = loanStore()
const pop = usePopup()

const loading = ref(false)
const errorMessage = ref(null)
const patronInput = ref<InstanceType<typeof Input>>()
const preview = ref<{
  patron: User
  accession: Accession
} | null>(null)

const emptyForm = () => ({
  patron: '',
  accession: '',
  // patron: 'student10',
  // accession: 'GC000025',
})

const form = reactive(emptyForm())

function clear() {
  Object.assign(form, emptyForm())
  preview.value = null
  errorMessage.value = null

  nextTick()

  patronInput.value?.input?.focus()
}

async function review() {
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

async function continueCheckout() {
  
}

// interface PatronPreview {
//   ebc_number: string | null
//   user: {
//     first_name: string
//     middle_initial: string | null
//     last_name: string | null
//   }
//   patron_type: { name: string } | null
//   program: { name: string; department: { name: string } | null } | null
// }

// interface AccessionPreview {
//   accession_number: string | null
//   status: string
//   item: {
//     title: string
//     subtitle: string | null
//     call_number: string | null
//   }
// }

// interface CirculationPreview {
//   patron: PatronPreview
//   accession: AccessionPreview
// }

// interface ApiResponse {
//   message: string
//   data: CirculationPreview
// }

// const patronIdentifier = ref('')
// const accessionNumber = ref('')
// const preview = ref<CirculationPreview | null>(null)
// const loading = ref(false)
// const errorMessage = ref('')

// const patronName = computed(() => {
//   if (!preview.value) return ''

//   return [
//     preview.value.patron.user.first_name,
//     preview.value.patron.user.middle_initial,
//     preview.value.patron.user.last_name,
//   ].filter(Boolean).join(' ')
// })

// async function checkPreview() {
//   const patron = patronIdentifier.value.trim()
//   const accession = accessionNumber.value.trim()

//   if (!patron || !accession) return

//   loading.value = true
//   errorMessage.value = ''
//   preview.value = null

//   try {
//     const response = await api.post<ApiResponse>('circulation/preview', {
//       patron_identifier: patron,
//       accession_number: accession,
//     })

//     preview.value = response.data.data
//   } catch (error) {
//     if (axios.isAxiosError<ApiResponse>(error)) {
//       errorMessage.value = error.response?.data?.message ?? 'Unable to check this patron and item.'
//     } else {
//       errorMessage.value = 'Unable to check this patron and item.'
//     }
//   } finally {
//     loading.value = false
//   }
// }
</script>

<style scoped></style>
