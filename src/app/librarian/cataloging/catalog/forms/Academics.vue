<template>
  <Form id="academic-form" cols="2" @submit="submitForm">
    <div class="flex items-center justify-end">
      <Button type="button" left-icon="x-lg" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
    </div>

    <template #body>
      <!-- Basic Information -->
      <SectionHeader title="Basic Information" />

      <Control direction="col" class="col-span-2">
        <Label required id="academic-title">Title</Label>
        <Input id="academic-title" v-model="form.title" placeholder="Enter the title..." required />
      </Control>
      <Control direction="col" class="col-span-2">
        <Label id="academic-subtitle">Subtitle</Label>
        <Input id="academic-subtitle" v-model="form.subtitle" placeholder="Enter the subtitle..." />
      </Control>
      <Control direction="col" class="col-span-2">
        <Label id="academic-description">Description</Label>
        <Textarea id="academic-description" v-model="form.description" placeholder="Enter a description..." />
      </Control>
      <Control direction="col">
        <Label required id="academic-language">Language</Label>
        <Input id="academic-language" v-model="form.language" placeholder="Enter the language..." required />
      </Control>
      <Control direction="col">
        <Label id="academic-publication-year">Publication Year</Label>
        <Input id="academic-publication-year" v-model="form.publication_year" type="number" min="1900" placeholder="e.g. 2026" />
      </Control>
      <Control direction="col" class="col-span-2">
        <Label id="academic-keywords">Keywords</Label>
        <Input id="academic-keywords" v-model="form.keywords" placeholder="Enter keywords..." />
      </Control>

      <!-- Classification -->
      <SectionHeader title="Classification" />

      <Control direction="col">
        <Label required id="academic-item-type">Item Type ID</Label>
        <Input id="academic-item-type" v-model="form.item_type_id" type="number" min="1" placeholder="Enter item type ID..." required />
      </Control>
      <Control direction="col">
        <Label required id="academic-item-category">Item Type Category ID</Label>
        <Input id="academic-item-category" v-model="form.item_type_category_id" type="number" min="1" placeholder="Enter category ID..." required />
      </Control>
      <Control direction="col">
        <Label id="academic-subjects">Subjects</Label>
        <Input id="academic-subjects" v-model="form.subjects" placeholder="Enter subjects..." />
      </Control>
      <Control direction="col">
        <Label id="academic-doi">DOI</Label>
        <Input id="academic-doi" v-model="form.doi" placeholder="Enter the DOI..." />
      </Control>

      <!-- Location & Ownership -->
      <SectionHeader title="Location & Ownership" />

      <Control direction="col">
        <Label required id="academic-branch">Branch ID</Label>
        <Input id="academic-branch" v-model="form.branch_id" type="number" min="1" placeholder="Enter branch ID..." required />
      </Control>
      <Control direction="col">
        <Label required id="academic-department">Department ID</Label>
        <Input id="academic-department" v-model="form.department_id" type="number" min="1" placeholder="Enter department ID..." required />
      </Control>
      <Control direction="col">
        <Label id="academic-call-number">Call Number</Label>
        <Input id="academic-call-number" v-model="form.call_number" placeholder="Enter the call number..." />
      </Control>

      <!-- Authorship -->
      <SectionHeader title="Authorship" />

      <Control direction="col" class="col-span-2">
        <Label required id="academic-authors">Author IDs</Label>
        <Input id="academic-authors" v-model="form.author_ids" placeholder="Enter author IDs, separated by commas..." required />
      </Control>
    </template>

    <template #footer>
      <Button type="submit" variant="primary" form="academic-form">Submit</Button>
    </template>
  </Form>
</template>

<script setup lang="ts">
interface AcademicForm {
  title: string
  subtitle: string
  description: string
  call_number: string
  language: string
  publication_year: string
  keywords: string
  electronic_file: string
  item_type_id: string
  item_type_category_id: string
  branch_id: string
  subjects: string
  doi: string
  department_id: string
  author_ids: string
}

const emptyForm = (): AcademicForm => ({
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
  subjects: '',
  doi: '',
  department_id: '',
  author_ids: '',
})

const form = reactive<AcademicForm>(emptyForm())

const hasInputs = computed(() => Object.values(form).some((value) => String(value).trim().length > 0))

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

const submitForm = () =>
  console.log('Academic form submitted:', {
    ...form,
    subjects: form.subjects
      .split(',')
      .map((subject) => subject.trim())
      .filter(Boolean),
    author_ids: form.author_ids
      .split(',')
      .map((authorId) => Number(authorId.trim()))
      .filter(Boolean),
  })
</script>

<style scoped></style>
