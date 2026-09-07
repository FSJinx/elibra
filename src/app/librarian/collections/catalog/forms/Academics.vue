<template>
  <Form class="flex flex-col gap-3 p-5" @submit="submitForm">
    <SectionHeader class="bg-background border border-border rounded-t-xl rounded-b-lg" :title="`New ${$route.meta.breadcrumb}`" description="Please fill all required fields and re-check your inputs before submitting." />

    <!-- BASIC INFORMATION -->
    <BaseForm v-model="form" :errors="errors" />

    <!-- ACADEMIC INFORMATION -->

    <!-- CLASSIFICATION -->
    <ClassificationForm v-model="form" :errors="errors" :item_type_id="bookId" />

    <!-- AUTHOR -->
    <AuthorForm v-model="form" :errors="errors" :item_type_id="bookId" />

    <section class="sticky bottom-0 flex items-center justify-end gap-2 p-5 overflow-hidden border border-border rounded-b-xl bg-background z-2">
      <Button left-icon="x-lg" variant="danger" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
      <Button left-icon="archive" :disabled="!hasInputs" v-if="hasInputs">Save as Draft</Button>
      <Button type="submit" variant="primary" left-icon="send" :disabled="!hasInputs" v-if="hasInputs">Submit</Button>
    </section>
  </Form>
</template>

<script setup lang="ts">
import AuthorForm from '@/app/librarian/collections/catalog/forms/sections/AuthorForm.vue'
import BaseForm from '@/app/librarian/collections/catalog/forms/sections/BaseForm.vue'
import ClassificationForm from '@/app/librarian/collections/catalog/forms/sections/ClassificationForm.vue'
import type { AcademicField, AuthorField, BaseField, ClassficationField } from '@/app/librarian/collections/catalog/forms/form'

interface Form extends BaseField, AcademicField, ClassficationField, AuthorField {}

const { itemTypes } = itemTypeStore()

const pop = usePopup()
const auth = authStore()

const emptyForm = (): Form => ({
  title: 'e-Libra',
  subtitle: 'A Centralized Web-Based Integrated Library Management System and Resource Monitoring for Isabela State University',
  description: 'Si Wanda naging Scarlet Witch na talaga.',
  call_number: 'Mom.12DS',
  publication_year: '1998',
  electronic_file: null,
  keywords: [],

  item_type_category_id: '1',
  branch_id: auth.user?.role === 'librarian' ? auth.user?.branch?.id : '',
  language_id: '',

  doi: '',
  department_id: null,
  authors: [],
})

const form = reactive<Form>(emptyForm())
const errors = ref<Form>({
  title: '',
  subtitle: '',
  description: '',
  call_number: '',
  publication_year: '',
  electronic_file: null,
  keywords: [],

  item_type_category_id: '',
  branch_id: '',
  language_id: '',

  doi: '',
  authors: [],
})

const hasInputs = computed(() => Object.values(form).some((field) => (Array.isArray(field) ? field.length > 0 : field != null && String(field).trim().length > 0)))

const clearErrors = () => {
  Object.keys(errors.value).forEach((field) => {
    ;(errors.value as Record<string, unknown>)[field] = ''
  })
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

const bookId = computed(() => {
  const book = itemTypes?.find((i) => i.name === 'academic')
  return book?.id
})

async function submitForm() {
  clearErrors()

  const res = await pop.confirm({ text: 'Are you sure you have confirmed the inputs before submitting?' })

  if (res.isConfirmed) {
    pop.load()
    console.log({ ...form, branch_id: auth.user?.id, item_type_id: bookId.value })

    try {
      await api.post('item/create/academic', { ...form, item_type_id: bookId.value })
      pop.success('Book added successfully!')
      router.replace({ name: 'librarian.collections.catalog' })
    } catch (e: any) {
      const res = e.response.data
      console.log(res.errors)
      errors.value = res.errors
    }
  }
}
</script>

<style scoped>
.control {
  display: grid !important;
  grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  padding: 1.25rem;
}
</style>
