<template>
  <Form id="serial-form" cols="2" @submit="submitForm">
    <div class="flex items-center justify-end">
      <Button type="button" left-icon="x-lg" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
    </div>
    <template #body>
      <Control direction="col">
        <Label required id="serial-title">Title</Label>
        <Input id="serial-title" v-model="form.title" placeholder="Enter the title..." required />
      </Control>
      <Control direction="col">
        <Label id="serial-subtitle">Subtitle</Label>
        <Input id="serial-subtitle" v-model="form.subtitle" placeholder="Enter the subtitle..." />
      </Control>
      <Control direction="col" class="col-span-2">
        <Label id="serial-description">Description</Label>
        <Input id="serial-description" v-model="form.description" placeholder="Enter a description..." />
      </Control>
      <Control direction="col">
        <Label id="serial-call-number">Call Number</Label>
        <Input id="serial-call-number" v-model="form.call_number" placeholder="Enter the call number..." />
      </Control>
      <Control direction="col">
        <Label required id="serial-language">Language</Label>
        <Input id="serial-language" v-model="form.language" placeholder="Enter the language..." required />
      </Control>
      <Control direction="col">
        <Label id="serial-publication-year">Publication Year</Label>
        <Input id="serial-publication-year" v-model="form.publication_year" type="number" min="1900" placeholder="e.g. 2026" />
      </Control>
      <Control direction="col">
        <Label id="serial-keywords">Keywords</Label>
        <Input id="serial-keywords" v-model="form.keywords" placeholder="Enter keywords..." />
      </Control>
      <Control direction="col">
        <Label required id="serial-item-type">Item Type ID</Label>
        <Input id="serial-item-type" v-model="form.item_type_id" type="number" min="1" placeholder="Enter item type ID..." required />
      </Control>
      <Control direction="col">
        <Label required id="serial-item-category">Item Type Category ID</Label>
        <Input id="serial-item-category" v-model="form.item_type_category_id" type="number" min="1" placeholder="Enter category ID..." required />
      </Control>
      <Control direction="col">
        <Label required id="serial-branch">Branch ID</Label>
        <Input id="serial-branch" v-model="form.branch_id" type="number" min="1" placeholder="Enter branch ID..." required />
      </Control>
      <Control direction="col">
        <Label id="serial-isbn-issn">ISBN/ISSN</Label>
        <Input id="serial-isbn-issn" v-model="form.isbn_issn" placeholder="Enter ISBN/ISSN..." />
      </Control>
      <Control direction="col">
        <Label id="serial-volume">Volume</Label>
        <Input id="serial-volume" v-model="form.volume" placeholder="Enter the volume..." />
      </Control>
      <Control direction="col">
        <Label id="serial-issue">Issue</Label>
        <Input id="serial-issue" v-model="form.issue" placeholder="Enter the issue..." />
      </Control>
      <Control direction="col">
        <Label id="serial-pages">Pages</Label>
        <Input id="serial-pages" v-model="form.pages" placeholder="Enter the pages..." />
      </Control>
      <Control direction="col">
        <Label id="serial-doi">DOI</Label>
        <Input id="serial-doi" v-model="form.doi" placeholder="Enter the DOI..." />
      </Control>
    </template>
    <template #footer>
      <Button type="submit" variant="primary" form="serial-form">Submit</Button>
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
  isbn_issn: '',
  volume: '',
  issue: '',
  pages: '',
  doi: '',
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
      isbn_issn: '',
      volume: '',
      issue: '',
      pages: '',
      doi: '',
    })
  }
}

const submitForm = () => console.log('Serial form submitted:', { ...form })
</script>

<style scoped></style>
