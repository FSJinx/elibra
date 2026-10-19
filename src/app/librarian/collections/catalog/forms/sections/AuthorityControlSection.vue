<template>
  <Card class="rounded-none">
    <CardHeader>
      <h2 class="text-xl font-semibold text-primary">Authority Control</h2>
      <p class="mt-0.5 text-sm text-foreground-secondary">Authority control for this catalog record</p>
    </CardHeader>

    <CardBody class="space-y-5">
      <!-- Item Language -->
      <Control id="item-language_id">
        <Label>Language</Label>
        <Select id="item-language_id" v-model="form.language_id" :error="getError(errors, 'language_id')" :disabled="!editing">
          <Option value="" disabled>Select a language</Option>
          <Option v-for="language in options.languages" :key="language.id" :value="language.id">{{ language.name }}</Option>
        </Select>
      </Control>

      <!-- Keywords -->
      <Control id="item-keywords" required v-if="editing">
        <Label>Keywords / Subjects / Topics</Label>
        <div class="flex flex-col">
          <AddKeywords v-model="form" />
          <div class="flex flex-wrap gap-2 mt-5">
            <p class="text-center text-muted-foreground" v-if="!form.keywords?.length">No keywords yet.</p>
            <Chip v-for="tag in form.keywords?.slice(0, 1)" :key="tag">{{ tag }}</Chip>
            <Chip v-if="form.keywords?.length && form.keywords?.length > 1" :title="form.keywords?.slice(1).join(', ')">+{{ form.keywords.length - 1 }} more</Chip>
          </div>
        </div>
      </Control>
    </CardBody>
  </Card>
</template>

<script setup lang="ts">
import { getError, type Form } from '@/app/librarian/collections/catalog/forms/form'
import AddKeywords from '@/app/librarian/collections/catalog/forms/sections/modals/AddKeywords.vue'

withDefaults(
  defineProps<{
    editing?: boolean
    errors?: any
  }>(),
  {
    editing: true,
  },
)

const form = defineModel<Partial<Form>>({ default: {} })
const options = optionStore()
</script>
