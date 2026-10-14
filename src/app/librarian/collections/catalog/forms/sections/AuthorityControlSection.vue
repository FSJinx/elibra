<template>
  <Card class="rounded-none">
    <CardHeader>
      <h2 class="text-xl font-semibold text-foreground">Authority Control</h2>
      <p class="mt-0.5 text-sm text-foreground-secondary">Authority control for this catalog record</p>
    </CardHeader>

    <CardBody class="space-y-5">
      <!-- Item Language -->
      <Control id="item-language_id">
        <Label>Language</Label>
        <Select id="item-language_id" v-model="form.language_id" :error="getError(errors, 'language_id')">
          <Option value="" disabled>Select a language</Option>
          <Option v-for="language in languages.select()" :key="language.id" :value="language.id">{{ language.name }}</Option>
        </Select>
      </Control>

      <!-- Keywords -->
      <Control id="item-item_type_id" required>
        <Label>Keywords / Subjects / Topics</Label>
        <AddKeywords v-model="form" />
      </Control>
    </CardBody>
  </Card>
</template>

<script setup lang="ts">
import { getError, type Form } from '@/app/librarian/collections/catalog/forms/form'
import AddKeywords from '@/app/librarian/collections/catalog/forms/sections/modals/AddKeywords.vue'

defineProps<{
  errors: any
}>()

const form = defineModel<Partial<Form>>({ default: {} })
const languages = useLanguagesStore()

watch(
  () => form.value.item_type_id,
  () => {
    form.value.item_type_category_id = ''
  },
)
</script>
