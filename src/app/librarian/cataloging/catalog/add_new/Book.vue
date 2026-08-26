<template>
  <Form id="book-form" cols="2" @submit="submitForm">
    <div class="flex items-center justify-end gap-2">
      <Button type="button" left-icon="archive" :disabled="!hasInputs">Drafts</Button>
      <Button type="button" left-icon="x-lg" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
    </div>
    <template #body>
      <Control direction="col">
        <Label required id="book-title">Title</Label>
        <Input id="book-title" v-model="form.title" placeholder="Enter the title of the book..." required />
      </Control>
      <Control direction="col">
        <Label id="book-subtitle">Subtitle</Label>
        <Input id="book-subtitle" v-model="form.subtitle" placeholder="Enter the subtitle..." />
      </Control>
      <Control direction="col" class="col-span-2">
        <Label required id="book-description">Description</Label>
        <Input id="book-description" v-model="form.description" placeholder="Enter a description..." required />
      </Control>
      <Control direction="col">
        <Label id="book-call-number">Call Number</Label>
        <Input id="book-call-number" v-model="form.call_number" placeholder="Enter the call number..." />
      </Control>
      <Control direction="col">
        <Label required id="book-language">Language</Label>
        <Input id="book-language" v-model="form.language" placeholder="Enter the language..." required />
      </Control>
      <Control direction="col">
        <Label required id="book-publication-year">Publication Year</Label>
        <Input id="book-publication-year" v-model="form.publication_year" type="number" min="1900" placeholder="e.g. 2026" required />
      </Control>
      <Control direction="col">
        <Label required id="book-keywords">Keywords</Label>
        <Input id="book-keywords" v-model="form.keywords" placeholder="Enter keywords..." required />
      </Control>
      <Control direction="col">
        <Label required id="book-item-type">Item Type ID</Label>
        <Input id="book-item-type" v-model="form.item_type_id" type="number" min="1" placeholder="Enter item type ID..." required />
      </Control>
      <Control direction="col">
        <Label required id="book-item-category">Item Type Category ID</Label>
        <Input id="book-item-category" v-model="form.item_type_category_id" type="number" min="1" placeholder="Enter category ID..." required />
      </Control>
      <Control direction="col">
        <Label required id="book-branch">Branch ID</Label>
        <Input id="book-branch" v-model="form.branch_id" type="number" min="1" placeholder="Enter branch ID..." required />
      </Control>
      <Control direction="col">
        <Label id="book-electronic-file">Electronic File</Label>
        <Input id="book-electronic-file" v-model="form.electronic_file" placeholder="Enter the file path..." />
      </Control>
    </template>
    <template #footer>
      <Button left-icon="folder2-open" form="book-form">Save as Draft</Button>
      <Button type="submit" variant="primary" left-icon="send" form="book-form">Submit</Button>
    </template>
  </Form>
</template>

<script setup lang="ts">
const form = reactive({
  title: '',
  subtitle: '',
  description: '',
  call_number: '',
  language: '',
  publication_year: '',
  keywords: '',
  electronic_file: '',
  item_type_id: '',
  item_type_category_id: '',
  branch_id: '',
})

const hasInputs = computed(() => Object.values(form).some((value) => String(value).trim().length > 0))

const clearForm = async () => {
  const result = await usePopup().confirm({
    title: 'Clear Form?',
    text: 'Are you sure you want to clear the inputs in this form? You will lose all your progress, you can save it as draft instead.',
    confirmButtonText: 'Clear Form',
  })

  if (result.isConfirmed) {
    Object.assign(form, {
      title: '',
      subtitle: '',
      description: '',
      call_number: '',
      language: '',
      publication_year: '',
      keywords: '',
      electronic_file: '',
      item_type_id: '',
      item_type_category_id: '',
      branch_id: '',
    })
  }
}

const submitForm = () => console.log('Book form submitted:', { ...form })

onBeforeRouteLeave(async () => {
  if (!hasInputs.value) return true

  const result = await usePopup().confirm({
    title: 'Leave Form?',
    text: 'You have unsaved inputs. Are you sure you want to continue? You will lose your progress.',
    confirmButtonText: 'Leave',
  })

  return result.isConfirmed
})
</script>

<style scoped></style>
