<template>
  <Form id="academic-form" cols="2" @submit="submitForm">
    <SectionHeader class="sticky top-0 bg-background z-99 shadow-md" :title="`New ${$route.meta.breadcrumb}`" description="Please fill all required fields and re-check your inputs before submitting.">
      <div class="flex items-center justify-end gap-2">
        <Button type="button" left-icon="x-lg" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
        <Button type="button" left-icon="archive" :disabled="!hasInputs" v-if="hasInputs">Save as Draft</Button>
        <Button type="button" variant="primary" left-icon="send" :disabled="!hasInputs" v-if="hasInputs">Submit</Button>
      </div>
    </SectionHeader>

    <!-- Basic Information -->
    <div class="flex flex-col gap-5 p-10">
      <div class="grid grid-cols-2 gap-3">
        <!-- Header -->
        <h1 class="text-info col-span-2 font-semibold text-2xl">Basic Information</h1>

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
          <Select id="academic-language" v-model="form.language">
            <Option value="">Select a language</Option>
          </Select>
        </Control>
        <Control direction="col">
          <Label id="academic-publication-year">Publication Year</Label>
          <Input id="academic-publication-year" v-model="form.publication_year" type="number" min="1900" placeholder="e.g. 2026" />
        </Control>
        <Control direction="col" class="col-span-2">
          <Label id="academic-keywords">Keywords</Label>
          <Input id="academic-keywords" v-model="form.keywords" placeholder="Enter keywords..." />
        </Control>
      </div>

      <!-- Classification -->
      <div class="grid grid-cols-2 gap-3">
        <h1 class="py-2 text-info col-span-2 font-semibold text-2xl">Classification</h1>
        <Control direction="col">
          <Label required id="academic-item-category">Item Type Category ID</Label>
          <Select id="academic-item-category" v-model="form.item_type_category_id">
            <Option value="">Select Item Category</Option>
          </Select>
        </Control>
        <Control direction="col">
          <Label id="academic-doi">DOI</Label>
          <Input id="academic-doi" v-model="form.doi" placeholder="Enter the DOI..." />
        </Control>
      </div>

      <!-- Location & Ownership -->
      <div class="grid grid-cols-2 gap-3">
        <h1 class="py-2 text-info col-span-2 font-semibold text-2xl">Location & Ownership</h1>

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
      </div>

      <!-- Authorship -->
      <div class="grid grid-cols-1 gap-3">
        <!-- Authorship -->
        <h1 class="py-2 text-info col-span-2 font-semibold text-2xl">Authorship</h1>

        <Control direction="col" class="col-span-2">
          <Label required id="academic-authors">Author IDs</Label>
          <Input id="academic-authors" v-model="form.author_ids" placeholder="Enter author IDs, separated by commas..." required />
        </Control>

        <Table title="" subtitle="" class="colspan">
          <Thead>
            <tr>
              <Th class="text-left">Name</Th>
              <Th>Authorship</Th>
            </tr>
          </Thead>
          <Tbody :data="form.author_ids" :loading="false" :cols="2">
            <tr v-for="(item, index) in form.author_ids">
              <Td class="text-left"></Td>
              <Td></Td>
            </tr>
          </Tbody>
        </Table>
      </div>

      <!-- Subjects -->
      <div class="grid grid-cols-2 gap-3">
        <div class="flex items-center justify-between col-span-2">
          <h1 class="py-2 text-info col-span-2 font-semibold text-2xl">Topical Keywords/Subjects</h1>
          <SubjectModal v-model="form.subjects" />
        </div>

        <div class="text-muted-foreground" v-if="form.subjects.length === 0">No subjects yet.</div>
        <div class="flex items-center gap-2" v-else>
          <template v-for="subject in form.subjects" :key="subject">
            <Chip removable @remove="removeSubject(subject)">{{ subject }}</Chip>
          </template>
        </div>
      </div>
    </div>
  </Form>
</template>

<script setup lang="ts">
import SubjectModal from '@/app/librarian/collections/catalog/modals/SubjectModal.vue'

const pop = usePopup()

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
  subjects: string[]
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
  subjects: [],
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
    // subjects: form.subjects
    //   .split(',')
    //   .map((subject) => subject.trim())
    //   .filter(Boolean),
    // author_ids: form.author_ids
    //   .split(',')
    //   .map((authorId) => Number(authorId.trim()))
    //   .filter(Boolean),
  })

async function removeSubject(subject: string) {
  const res = await pop.confirm({ title: 'Delete', text: `Are you sure you want to delete ${subject}?` })

  if (res.isConfirmed) {
    form.subjects = form.subjects.filter((item) => item !== subject)
  }
}
</script>

<style scoped></style>
