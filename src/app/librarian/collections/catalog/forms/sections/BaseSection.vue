<template>
  <Card>
    <CardHeader>
      <h2 class="text-xl font-semibold text-foreground">General Information and Classification</h2>
      <p class="mt-0.5 text-sm text-foreground-secondary">Bibliographic and classification information for this catalog record.</p>
    </CardHeader>

    <CardBody class="space-y-5">
      <!-- Item Type -->
      <Control id="item-item_type_id" required>
        <Label>Item Type</Label>
        <Select v-model="form.item_type_id" required>
          <Option value="" disabled>Select an item type</Option>
          <Option v-for="itemType in item_types.select()" :key="itemType.id" :value="itemType.id">{{ itemType.name }}</Option>
        </Select>
      </Control>

      <!-- Item Category -->
      <Control id="item-item_type_category_id" required>
        <Label>Category</Label>
        <Select id="item-item_type_category_id" v-model="form.item_type_category_id" required>
          <Option value="" disabled>{{ form.item_type_id ? 'Select a category' : 'Please select an item type before selecting item category' }}</Option>
          <Option v-for="category in categories.select(form.item_type_id)" :key="category.id" :value="category.id">{{ category.name }}</Option>
        </Select>
      </Control>

      <!-- Item Title -->
      <Control id="item-title" required>
        <Label>Title</Label>
        <Input v-model="form.title" type="text" placeholder="Enter item's title here..." required />
      </Control>

      <!-- Item Subtitle -->
      <Control id="item-subtitle" nullable>
        <Label>Subtitle</Label>
        <Textarea v-model="form.subtitle" type="text" placeholder="Enter item's subtitle here..." />
      </Control>

      <!-- Item Description -->
      <Control id="item-description" nullable>
        <Label>Abstract / Description / Summary</Label>
        <Textarea v-model="form.description" placeholder="Enter item's description here..." />
      </Control>

      <!-- Call Number -->
      <Control id="item-call_number" required>
        <Label>Call Number</Label>
        <Input v-model="form.call_number" type="text" placeholder="Enter item's call number..." />
      </Control>

      <!-- ISBN OR ISSN -->
      <Control id="item-isbn_issn">
        <Label>ISBN or ISSN</Label>
        <Input v-model="form.isbn_issn" type="text" placeholder="Enter ISBN or ISSN..." :error="getError(errors, 'isbn_issn')" />
      </Control>

      <!-- Item Cover -->
      <Control id="item-electronic_file" nullable>
        <Label>Item Cover</Label>
        <InputFile v-model="form.electronic_file" />
      </Control>
    </CardBody>
  </Card>
</template>

<script setup lang="ts">
import { getError, type Form } from '@/app/librarian/collections/catalog/forms/form'

defineProps<{
  errors?: any
}>()

const form = defineModel<Partial<Form>>({ default: {} })
const item_types = useItemTypeStore()
const categories = useItemCategoriesStore()

watch(
  () => form.value.item_type_id,
  () => {
    form.value.item_type_category_id = ''
  },
)
</script>
