<template>
  <section class="overflow-hidden border border-border bg-background rounded-lg">
    <div class="border-b border-border p-5">
      <h2 class="text-xl font-semibold text-foreground">Basic Information</h2>

      <p class="mt-0.5 text-sm text-foreground-secondary">Bibliographic information for this catalog record</p>
    </div>

    <div class="divide-y divide-border">
      <Control class="control">
        <Label id="book-title">Title</Label>
        <Input id="book-title" placeholder="Enter book's title..." v-model="form.title" :error="errors.title?.[0]" />
      </Control>
      <Control class="control">
        <Label id="book-subtitle" class="mb-auto">Subtitle</Label>
        <Textarea id="book-subtitle" placeholder="Enter book's subtitle..." v-model="form.subtitle" :error="errors.subtitle" />
      </Control>
      <Control class="control">
        <div class="w-75 mb-auto p-2 pl-0">
          <Label id="book-description">Description</Label>
          <p class="text-sm text-muted-foreground">Abstract or Description of the Book</p>
        </div>
        <Textarea id="book-description" placeholder="Enter book's description..." v-model="form.description" :error="errors.description"></Textarea>
      </Control>
      <Control class="control">
        <Label id="book-call_number">Call Number</Label>
        <Input id="book-call_number" placeholder="Enter book's call number..." v-model="form.call_number" :error="errors.call_number?.[0]" />
      </Control>
      <Control class="control">
        <Label id="book-publication_year">Publication Year</Label>
        <Input id="book-publication_year" type="number" placeholder="Enter book's publication year..." v-model="form.publication_year" :error="errors.publication_year" />
      </Control>
      <Control class="control">
        <Label id="book-electronic_file" >Electronic File</Label>
        <InputFile id="book-electronic_file" v-model="form.electronic_file" />
        <p class="text-sm text-danger">{{ errors.electronic_file }}</p>
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
        <p class="text-danger text-sm" v-if="errors.keywords"></p>
      </Control>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { BaseField } from '@/app/librarian/collections/catalog/forms/form'
import SubjectModal from '@/app/librarian/collections/catalog/modals/SubjectModal.vue';

interface Props {
  errors?: any
}

const pop = usePopup()
const props = defineProps<Props>()

const form = defineModel<BaseField>({ default: {} })

async function removeSubject(key: string) {
  const res = await pop.confirm({ text: `Are you sure you want to remove "${key}" from keywords?` })

  if (res.isConfirmed) {
    form.value.keywords = form.value.keywords.filter((i) => i !== key)
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
