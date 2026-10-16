<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader title="Check In & Check Out" description="Check in or out materials in this library" icon="journal-plus"></SectionHeader>

    <div class="flex-1 flex items-start gap-5 p-5">
      <Card class="w-150 shrink-0">
        <CardHeader>Loan Form</CardHeader>
        <CardBody>
          <Form class="flex flex-col gap-5" id="issue-form" @submit="checkPreview">
            <Control id="patron-id" col required>
              <Label>Patron</Label>
              <Input v-model="patronIdentifier" type="text" placeholder="Enter patron's ID Number or EBC" auto-focus required />
            </Control>
            <Control id="item-accession" col required>
              <Label>Item</Label>
              <Input v-model="accessionNumber" type="text" placeholder="Enter item's accession code or barcode number" required />
            </Control>
          </Form>
        </CardBody>
        <CardFooter class="flex justify-end gap-2">
          <Button form="issue-form" type="submit" variant="success" :disabled="loading">
            {{ loading ? 'Checking...' : 'Check' }}
          </Button>
        </CardFooter>
      </Card>

      <Card class="w-full">
        <CardHeader>
          Loan Information
          <p class="text-sm text-muted-foreground">Preview of patron and item to loan.</p>
        </CardHeader>

        <CardBody v-if="!preview">
          <div class="text-center p-5">
            <p v-if="errorMessage" class="mb-2 text-danger">{{ errorMessage }}</p>
            No item on the preview.
          </div>
        </CardBody>

        <CardBody class="flex flex-col gap-5" v-else>
          <Title :level="1">Loan's Preview</Title>

          <h5 class="uppercase text-muted-foreground font-semibold">Patron's Information</h5>
          <div class="flex items-start gap-3 border border-border p-5 rounded-2xl">
            <!-- Patron's Photo -->
            <div class="shrink-0 size-30 max-w-30 border border-border rounded-full overflow-hidden shadow">
              <img :src="user" alt="" class="max-w-full" loading="lazy" />
            </div>
            <!-- Patron's Information -->
            <div class="flex flex-col p-2">
              <Title :level="2" class="mb-2">{{ patronName }}</Title>

              <p><span class="font-semibold">Patron Type:</span> {{ preview.patron.patron_type?.name ?? '—' }}</p>
              <p><span class="font-semibold">Department:</span> {{ preview.patron.program?.department?.name ?? '—' }}</p>
              <p><span class="font-semibold">Program:</span> {{ preview.patron.program?.name ?? '—' }}</p>
              <p><span class="font-semibold">EBC:</span> {{ preview.patron.ebc_number ?? '—' }}</p>
            </div>
          </div>

          <h5 class="uppercase text-muted-foreground font-semibold">Item's Information</h5>
          <div class="flex items-start gap-3 border border-border p-5 rounded-2xl">
            <!-- Item's Photo -->
            <div class="shrink-0 w-30 max-w-30 border border-border rounded-xl overflow-hidden shadow">
              <img :src="book" alt="" class="max-w-full" loading="lazy" />
            </div>
            <!-- Item's Information -->
            <div class="flex flex-col p-2">
              <Title :level="2">{{ preview.accession.item.title }}</Title>
              <p v-if="preview.accession.item.subtitle">{{ preview.accession.item.subtitle }}</p>
              <p><span class="font-semibold">Accession:</span> {{ preview.accession.accession_number }}</p>
              <p><span class="font-semibold">Call Number:</span> {{ preview.accession.item.call_number ?? '—' }}</p>
              <p><span class="font-semibold">Status:</span> {{ preview.accession.status }}</p>
            </div>
          </div>
        </CardBody>
      </Card>
    </div>
  </div>
</template>

<script setup lang="ts">
import book from '@/assets/images/default_book.png'
import user from '@/assets/images/user.png'
import axios from 'axios'
import { api } from '@/plugins/axios'

interface PatronPreview {
  ebc_number: string | null
  user: {
    first_name: string
    middle_initial: string | null
    last_name: string | null
  }
  patron_type: { name: string } | null
  program: { name: string; department: { name: string } | null } | null
}

interface AccessionPreview {
  accession_number: string | null
  status: string
  item: {
    title: string
    subtitle: string | null
    call_number: string | null
  }
}

interface CirculationPreview {
  patron: PatronPreview
  accession: AccessionPreview
}

interface ApiResponse {
  message: string
  data: CirculationPreview
}

const patronIdentifier = ref('')
const accessionNumber = ref('')
const preview = ref<CirculationPreview | null>(null)
const loading = ref(false)
const errorMessage = ref('')

const patronName = computed(() => {
  if (!preview.value) return ''

  return [
    preview.value.patron.user.first_name,
    preview.value.patron.user.middle_initial,
    preview.value.patron.user.last_name,
  ].filter(Boolean).join(' ')
})

async function checkPreview() {
  const patron = patronIdentifier.value.trim()
  const accession = accessionNumber.value.trim()

  if (!patron || !accession) return

  loading.value = true
  errorMessage.value = ''
  preview.value = null

  try {
    const response = await api.post<ApiResponse>('circulation/preview', {
      patron_identifier: patron,
      accession_number: accession,
    })

    preview.value = response.data.data
  } catch (error) {
    if (axios.isAxiosError<ApiResponse>(error)) {
      errorMessage.value = error.response?.data?.message ?? 'Unable to check this patron and item.'
    } else {
      errorMessage.value = 'Unable to check this patron and item.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<style scoped></style>
