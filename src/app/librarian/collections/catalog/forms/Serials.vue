<template>
  <Form class="flex flex-col gap-3 p-5" @submit="submitForm">
    <SectionHeader class="bg-background border border-border rounded-t-xl rounded-b-lg" :title="`New ${$route.meta.breadcrumb}`" description="Please fill all required fields and re-check your inputs before submitting." />

    <BaseForm v-model="form" :errors="errors" />

    <section class="overflow-hidden border border-border bg-background rounded-lg">
      <div class="border-b border-border p-5">
        <h2 class="text-xl font-semibold text-foreground">Serial Information</h2>
      </div>
      <div class="divide-y divide-border">
        <Control class="control">
          <Label id="serial-isbn_issn">ISBN/ISSN</Label>
          <Input id="serial-isbn_issn" v-model="form.isbn_issn" placeholder="Enter ISBN or ISSN..." :error="errors.isbn_issn?.[0]" />
        </Control>
        <Control class="control">
          <Label id="serial-volume">Volume</Label>
          <Input id="serial-volume" v-model="form.volume" placeholder="Enter the volume..." :error="errors.volume?.[0]" />
        </Control>
        <Control class="control">
          <Label id="serial-issue">Issue</Label>
          <Input id="serial-issue" v-model="form.issue" placeholder="Enter the issue..." :error="errors.issue?.[0]" />
        </Control>
        <Control class="control">
          <Label id="serial-pages">Pages</Label>
          <Input id="serial-pages" v-model="form.pages" placeholder="Enter the pages..." :error="errors.pages?.[0]" />
        </Control>
        <Control class="control">
          <Label id="serial-doi">DOI</Label>
          <Input id="serial-doi" v-model="form.doi" placeholder="Enter the DOI..." :error="errors.doi?.[0]" />
        </Control>
      </div>
    </section>

    <ClassificationForm v-model="form" :errors="errors" :item_type_id="serialId" />
    <AuthorForm v-model="form" :errors="errors" :item_type_id="serialId" />

    <section class="sticky bottom-0 flex items-center justify-end gap-2 p-5 overflow-hidden border border-border rounded-b-xl bg-background z-2">
      <Button left-icon="x-lg" variant="danger" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
      <Button type="submit" variant="primary" left-icon="send" :disabled="!hasInputs" v-if="hasInputs">Submit</Button>
    </section>
  </Form>
</template>

<script setup lang="ts">
import AuthorForm from '@/app/librarian/collections/catalog/forms/sections/AuthorForm.vue'
import BaseForm from '@/app/librarian/collections/catalog/forms/sections/BaseForm.vue'
import ClassificationForm from '@/app/librarian/collections/catalog/forms/sections/ClassificationForm.vue'
import type { AuthorField, BaseField, ClassficationField, SerialField } from '@/app/librarian/collections/catalog/forms/form'

interface Form extends BaseField, ClassficationField, SerialField, AuthorField {}

const { itemTypes } = useItemTypeStore()
const auth = authStore()
const pop = usePopup()

const emptyForm = (): Form => ({
  title: '',
  subtitle: '',
  description: '',
  call_number: '',
  language_id: '',
  publication_year: '',
  keywords: [],
  electronic_file: null,
  item_type_category_id: '',
  branch_id: auth.user?.role === 'librarian' ? auth.user?.branch?.id : '',
  isbn_issn: '',
  volume: '',
  issue: '',
  pages: '',
  doi: '',
  authors: [],
})

const form = reactive<Form>(emptyForm())
const errors = ref<Record<string, any>>({})

const hasInputs = computed(() => Object.values(form).some((value) => (Array.isArray(value) ? value.length > 0 : value != null && String(value).trim().length > 0)))

const clearErrors = () => {
  errors.value = {}
}

const clearForm = async () => {
  const result = await usePopup().confirm({
    title: 'Clear Form?',
    text: 'Are you sure you want to clear the inputs in this form? You will lose all your progress, you can save it as draft instead.',
    confirmButtonText: 'Clear Form',
  })

  if (result.isConfirmed) {
    Object.assign(form, emptyForm())
  }
}

async function submitForm() {
  clearErrors()

  const res = await pop.confirm({ text: 'Are you sure you have confirmed the inputs before submitting?' })

  if (!res.isConfirmed) {
    return
  }

  pop.load()

  try {
    const payload = {
      ...form,
      item_type_id: serialId.value,
    }

    await api.post('item/create/serial', payload)
    pop.success('Serial added successfully!')
    router.replace({ name: 'librarian.collections.catalog' })
  } catch (e: any) {
    errors.value = e.response?.data?.errors ?? {}
  }
}

const serialId = computed(() => itemTypes?.find((item) => item.name === 'serial')?.id)
</script>

<style scoped>
.control {
  display: grid !important;
  grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  padding: 1.25rem;
}
</style>
