<template>
  <section class="overflow-hidden border border-border bg-background rounded-lg">
    <div class="border-b border-border p-5">
      <h2 class="text-xl font-semibold text-foreground">Book Information</h2>

      <p class="mt-0.5 text-sm text-foreground-secondary">Bibliographic information for this catalog record</p>
    </div>

    <Control class="control">
      <Label id="book-edition" required>Edition</Label>
      <Input id="book-edition" type="text" placeholder="e.g. 1st ed." v-model="form.edition" required :error="errors.edition" />
    </Control>
    <Control class="control">
      <Label id="book-isbn_issn" required>ISBN_ISSN</Label>
      <Input id="book-isbn_issn" placeholder="Enter ISBN or ISSN" v-model="form.isbn_issn" required :error="errors.isbn_issn" />
    </Control>
    <Control class="control">
      <Label id="book-copyright_year">Copyright Year</Label>
      <Input id="book-copyright_year" placeholder="Enter book's copyright year..." v-model="form.copyright_year" required :error="errors.copyright_year" />
    </Control>
    <Control class="control">
      <Label id="book-doi">DOI</Label>
      <Input id="book-doi" placeholder="Enter book's DOI" v-model="form.doi" :error="errors.doi" />
    </Control>
  </section>
</template>

<script setup lang="ts">
import type { BookField } from '@/app/librarian/collections/catalog/forms/form'
interface Props {
  errors?: any
}

const props = defineProps<Props>()

const form = defineModel<BookField>({ default: {} })
</script>
