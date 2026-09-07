<template>
  <Form class="flex flex-col gap-3 p-5" @submit="submitForm">
    <SectionHeader class="bg-background border border-border rounded-t-xl rounded-b-lg" :title="`New ${$route.meta.breadcrumb}`" description="Please fill all required fields and re-check your inputs before submitting." />

    <!-- BASIC INFORMATION -->
    <section class="overflow-hidden border border-border bg-background rounded-lg">
      <div class="border-b border-border p-5">
        <h2 class="text-xl font-semibold text-foreground">Basic Information</h2>

        <p class="mt-0.5 text-sm text-foreground-secondary">Bibliographic information for this catalog record</p>
      </div>

      <div class="divide-y divide-border">
        <Control class="control">
          <Label id="book-title">Title</Label>
          <Input id="book-title" placeholder="Enter book's title..." v-model="form.title" />
        </Control>
        <Control class="control">
          <Label id="book-subtitle">Subtitle</Label>
          <Input id="book-subtitle" placeholder="Enter book's subtitle..." v-model="form.subtitle" />
        </Control>
        <Control class="control">
          <div class="w-75 mb-auto p-2 pl-0">
            <Label id="book-description">Description</Label>
            <p class="text-sm text-muted-foreground">Abstract or Description of the Book</p>
          </div>
          <Textarea id="book-description" placeholder="Enter book's description..." v-model="form.description"></Textarea>
        </Control>
        <Control class="control">
          <Label id="book-call_number">Call Number</Label>
          <Input id="book-call_number" placeholder="Enter book's call number..." v-model="form.call_number" />
        </Control>
        <Control class="control">
          <Label id="book-publication_year">Publication Year</Label>
          <Input id="book-publication_year" type="number" placeholder="Enter book's publication year..." v-model="form.publication_year" />
        </Control>
        <Control class="control">
          <Label id="book-electronic_file">Electronic File</Label>
          <InputFile id="book-electronic_file" v-model="form.electronic_file" />
        </Control>

        <Control class="control">
          <Label id="book-edition" required>Edition</Label>
          <Input id="book-edition" type="text" placeholder="e.g. 1st ed." v-model="form.edition" required />
        </Control>
        <Control class="control">
          <Label id="book-isbn_issn" required>ISBN_ISSN</Label>
          <Input id="book-isbn_issn" placeholder="Enter ISBN or ISSN" v-model="form.isbn_issn" required />
        </Control>
        <Control class="control">
          <Label id="book-copyright_year">Copyright Year</Label>
          <Input id="book-copyright_year" placeholder="Enter book's copyright year..." v-model="form.copyright_year" required />
        </Control>
        <Control class="control">
          <Label id="book-doi">DOI</Label>
          <Input id="book-doi" placeholder="Enter book's DOI" v-model="form.doi" />
        </Control>

        <Control class="control">
          <Label id="book-title" :class="[{ 'mb-auto': form.keywords.length !== 0 }]">Keywords</Label>
          <div class="flex justify-between gap-2" :class="[form.keywords.length === 0 ? 'items-center' : 'items-start']">
            <div class="flex flex-wrap gap-2">
              <p class="text-muted-foreground" v-if="form.keywords.length === 0">No topical keywords yet.</p>
              <Chip v-for="keyword in form.keywords" removable @remove="removeSubject(keyword)" v-else>{{ keyword }}</Chip>
            </div>
            <SubjectModal v-model="form.keywords" />
          </div>
        </Control>
      </div>
    </section>

    <!-- CLASSIFICATION -->
    <section class="overflow-hidden border border-border bg-background rounded-lg">
      <div class="border-b border-border p-5">
        <h2 class="text-xl font-semibold text-foreground">Classification</h2>

        <p class="mt-0.5 text-sm text-foreground-secondary">Classification and ownership information for this record</p>
      </div>

      <div class="divide-y divide-border">
        <Control class="control">
          <Label id="book-item_type_category_id" required>Category</Label>
          <Select id="book-item_type_category_id" class="capitalize" v-model="form.item_type_category_id" required>
            <Option value="" disabled>Select a category</Option>
            <Option class="capitalize" :value="category.id" v-for="category in categories">{{ category.name }}</Option>
          </Select>
        </Control>
        <Control class="control">
          <Label id="book-language">Language</Label>
          <Select id="book-language" class="capitalize" v-model="form.language_id">
            <Option value="" disabled>Select a language</Option>
            <Option class="capitalize" :value="language.id" v-for="language in languages">{{ language.name }}</Option>
          </Select>
        </Control>
      </div>
    </section>

    <!-- AUTHOR -->
    <Table title="Author" subtitle="Record the authors of this material">
      <template #header>
        <AddAuthor v-model="form.authors" />
      </template>
      <Thead>
        <tr>
          <Th>No.</Th>
          <Th class="text-left">Name</Th>
          <Th class="max-w-75">Authorship</Th>
        </tr>
      </Thead>
      <Tbody :data="form.authors" :loading="false" :cols="3">
        <tr v-for="(item, index) in form.authors" :key="item.id ?? index">
          <Td>{{ index + 1 }}</Td>
          <Td class="text-left">{{ item.first_name }} {{ item.last_name }}</Td>
          <Td>
            <Select :id="`authorship-${item.id ?? index}`" v-model="item.authorship_id" required>
              <Option value="" disabled>Select Authorship</Option>
              <Option v-for="i in authorshipsOption" :key="i.id" :value="i.id">{{ i.name }}</Option>
            </Select>
          </Td>
        </tr>
      </Tbody>
    </Table>

    <section class="sticky bottom-0 flex items-center justify-end gap-2 p-5 overflow-hidden border border-border rounded-b-xl bg-background">
      <Button left-icon="x-lg" :disabled="!hasInputs" @click="clearForm">Clear Form</Button>
      <Button left-icon="archive" :disabled="!hasInputs" v-if="hasInputs">Save as Draft</Button>
      <Button type="submit" variant="primary" left-icon="send" :disabled="!hasInputs" v-if="hasInputs">Submit</Button>
    </section>
  </Form>
</template>

<script setup lang="ts">
import AddAuthor from '@/app/librarian/collections/catalog/modals/AddAuthor.vue'
import SubjectModal from '@/app/librarian/collections/catalog/modals/SubjectModal.vue'
import type { Author } from '@/composables/data/useAuthor'

interface Form {
  // Basic Information
  title: string
  subtitle: string | null
  description: string | null
  call_number: string
  publication_year: string
  electronic_file: File[] | null
  keywords: string[]

  // Classification
  item_type_category_id: string
  branch_id: number | null
  language_id: string

  edition: string
  isbn_issn: string
  copyright_year: string
  doi: string
  authors: Author[]
}

const emptyForm = (): Form => ({
  title: 'Noli Me Tangere',
  subtitle: null,
  description: 'This is it pancit',
  call_number: 'Riz.192a',
  publication_year: '1992',
  electronic_file: null,
  keywords: [],

  item_type_category_id: '',
  branch_id: null,
  language_id: '',

  edition: '1st ed.',
  isbn_issn: '1283hdkdaf02',
  copyright_year: '1992',
  doi: '',
  authors: [],
})

const { itemCategories } = itemCategoriesStore()
const { itemTypes } = itemTypeStore()
const { authorships } = authorshipStore()
const { languages } = languagesStore()

const pop = usePopup()
const auth = authStore()

const form = reactive<Form>(emptyForm())

const hasInputs = computed(() => Object.values(form).some((field) => (Array.isArray(field) ? field.length > 0 : field != null && String(field).trim().length > 0)))
const categories = computed(() => itemCategories.filter((i) => i.item_type_id === bookId.value))
const authorshipsOption = computed(() => authorships?.filter((i) => i.item_type_id === bookId.value))

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
  const book = itemTypes?.find((i) => i.name === 'book')
  return book?.id
})

async function removeSubject(key: string) {
  const res = await pop.confirm({ text: `Are you sure you want to remove "${key}" from keywords?` })
  if (res.isConfirmed) {
    form.keywords = form.keywords.filter((i) => i !== key)
  }
}

async function submitForm() {
  const res = await pop.confirm({ text: 'Are you sure you have confirmed the inputs before submitting?' })

  if (res.isConfirmed) {
    pop.load()
    console.log({ ...form, branch_id: auth.user?.id, item_type_id: bookId.value })

    try {
      await api.post('item/create/book', { ...form, branch_id: auth.user?.id, item_type_id: bookId.value })
      pop.success('Book added successfully!')
    } catch {}
  }
}

// watch(
//   () => form,
//   () => console.log(form, bookId),
//   { deep: true, immediate: true },
// )
</script>

<style scoped>
.control {
  display: grid !important;
  grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  padding: 1.25rem;
}
</style>
