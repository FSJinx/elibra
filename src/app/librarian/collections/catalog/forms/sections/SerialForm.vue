<template>
  <section class="overflow-hidden border border-border bg-background rounded-lg">
    <div class="border-b border-border p-5">
      <h2 class="text-xl font-semibold text-foreground">Serial Information</h2>
    </div>
    <div class="divide-y divide-border">
      <Control>
        <Label id="serial-isbn_issn">ISBN/ISSN</Label>
        <Input id="serial-isbn_issn" v-model="form.isbn_issn" placeholder="Enter ISBN or ISSN..." :error="errors.isbn_issn?.[0]" />
      </Control>
      <Control>
        <Label id="serial-volume">Volume</Label>
        <Input id="serial-volume" v-model="form.volume" placeholder="Enter the volume..." :error="errors.volume?.[0]" />
      </Control>
      <Control>
        <Label id="serial-issue">Issue</Label>
        <Input id="serial-issue" v-model="form.issue" placeholder="Enter the issue..." :error="errors.issue?.[0]" />
      </Control>
      <Control>
        <Label id="serial-pages">Pages</Label>
        <Input id="serial-pages" v-model="form.pages" placeholder="Enter the pages..." :error="errors.pages?.[0]" />
      </Control>
      <Control>
        <Label id="serial-doi">DOI</Label>
        <Input id="serial-doi" v-model="form.doi" placeholder="Enter the DOI..." :error="errors.doi?.[0]" />
      </Control>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { SerialField } from '@/app/librarian/collections/catalog/forms/form'
interface Props {
  errors?: any
}

const props = defineProps<Props>()

const form = defineModel<SerialField>({ default: {} })
</script>
<style scoped>
.control {
  padding: 1.25rem;
}
</style>
