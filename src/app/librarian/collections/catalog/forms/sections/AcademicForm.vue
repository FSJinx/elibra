<template>
  <section class="overflow-hidden border border-border bg-background rounded-lg">
    <div class="border-b border-border p-5">
      <h2 class="text-xl font-semibold text-foreground">Academic Information</h2>

      <p class="mt-0.5 text-sm text-foreground-secondary">Bibliographic information for this catalog record</p>
    </div>

    <Control>
      <Label id="book-doi">Department</Label>
      <Input id="book-doi" placeholder="Enter book's DOI" v-model="form.department_id" :error="errors.department_id" />
    </Control>
    <Control>
      <Label id="book-doi">DOI</Label>
      <Input id="book-doi" placeholder="Enter book's DOI" v-model="form.doi" :error="errors.doi" />
    </Control>
  </section>
</template>

<script setup lang="ts">
import type { AcademicField } from '@/app/librarian/collections/catalog/forms/form'
interface Props {
  errors?: any
}

const props = defineProps<Props>()

const form = defineModel<AcademicField>({ default: {} })
</script>

<style scoped>
.control {
  padding: 1.25rem;
}
</style>