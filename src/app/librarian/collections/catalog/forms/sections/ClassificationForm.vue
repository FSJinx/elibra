<template>
  <section class="overflow-hidden border border-border bg-background rounded-lg">
    <div class="border-b border-border p-5">
      <h2 class="text-xl font-semibold text-foreground">Classification</h2>

      <p class="mt-0.5 text-sm text-foreground-secondary">Classification and ownership information for this record</p>
    </div>

    <div class="divide-y divide-border">
      <Control class="control" v-if="auth.user?.role === 'admin'">
        <Label id="book-branch_id" required>Branch</Label>
        <Select id="book-branch_id" class="capitalize" v-model="form.branch_id" required :error="errors.branch_id?.[0]">
          <Option value="" disabled>Select a branch</Option>
          <Option class="capitalize" :value="branch.id" v-for="branch in branchOptions">{{ branch.name }}</Option>
        </Select>
      </Control>

      <Control class="control">
        <Label id="book-item_type_category_id" required>Category</Label>
        <Select id="book-item_type_category_id" class="capitalize" v-model="form.item_type_category_id" :error="errors.item_type_category_id?.[0]" required>
          <Option value="" disabled>Select a category</Option>
          <Option class="capitalize" :value="category.id" v-for="category in categories">{{ category.name }}</Option>
        </Select>
      </Control>

      <Control class="control">
        <Label id="book-language">Language</Label>
        <Select id="book-language" class="capitalize" v-model="form.language_id" :error="errors.language_id?.[0]">
          <Option value="" disabled>Select a language</Option>
          <Option class="capitalize" :value="language.id" v-for="language in languages">{{ language.name }}</Option>
        </Select>
      </Control>
    </div>
  </section>
</template>

<script setup lang="ts">
import type { ClassficationField } from '@/app/librarian/collections/catalog/forms/form'
interface Props {
  errors?: any
  item_type_id: any
}

const { languages } = useLanguagesStore()
const { itemCategories } = useItemCategoriesStore()
const { branches } = useBranchStore()
const auth = authStore()

const props = defineProps<Props>()

// Computed
const categories = computed(() => itemCategories.filter((i) => i.item_type_id === props.item_type_id))
const branchOptions = computed(() => branches.filter((i) => i.campus_id === auth.user?.campus_id))

const form = defineModel<ClassficationField>({ default: {} })
</script>
