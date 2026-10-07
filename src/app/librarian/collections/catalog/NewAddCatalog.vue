<template>
  <div class="relative flex gap-3 p-5 w-full max-w-6xl mx-auto">
    <Button class="sticky top-5" icon="arrow-left" as="link" :to="{ name: 'librarian.collections.catalog' }" variant="danger" data-title="Return to catalog " />
    <Form class="flex flex-col gap-5" @submit="submit">
      <SectionHeader class="bg-background border border-border rounded-xl pb-5" title="New Item" description="Please fill all required fields and re-check your inputs before submitting.">
        <div class="flex items-center justify-end gap-2">
          <Button left-icon="archive">Drafts</Button>
        </div>
      </SectionHeader>

      <!-- BASIC INFORMATION -->
      <BaseSection v-model="form" :errors="errors" />

      <!-- PHYSICAL DESCRIPTION INFORMATION -->
      <PhysicalDescriptionSection v-model="form" :errors="errors" />

      <!-- PHYSICAL DESCRIPTION INFORMATION -->
      <SerialSection v-model="form" :errors="errors" />

      <!-- AUTHOR CONTROL INFORMATION -->
      <AuthorityControlSection v-model="form" :errors="errors" />

      <!-- AUTHOR CONTROL INFORMATION -->
      <AuthorSection v-model="form" :errors="errors" />

      <Card class="sticky bottom-0 justify-end z-100">
        <Button type="submit" variant="primary" :disabled="!hasInputs">Create</Button>
      </Card>
    </Form>
  </div>
</template>

<script setup lang="ts">
import { emptyForm, getError, type Form } from '@/app/librarian/collections/catalog/forms/form'
import AuthorityControlSection from '@/app/librarian/collections/catalog/forms/sections/AuthorityControlSection.vue'
import AuthorSection from '@/app/librarian/collections/catalog/forms/sections/AuthorSection.vue'
import BaseSection from '@/app/librarian/collections/catalog/forms/sections/BaseSection.vue'
import PhysicalDescriptionSection from '@/app/librarian/collections/catalog/forms/sections/PhysicalDescriptionSection.vue'
import SerialSection from '@/app/librarian/collections/catalog/forms/sections/SerialSection.vue'

const pop = usePopup()
const item = useItemStore()

const form = reactive(emptyForm())
const errors = reactive<Record<string, unknown>>({})
const hasInputs = computed(() => {
  return Object.values(form).some((value) => {
    if (Array.isArray(value)) return value.length > 0
    if (typeof value === 'string') return value.trim().length > 0
    return value != null && typeof value !== 'boolean'
  })
})

async function submit() {
  const confirm = await pop.confirm({ text: 'Please confirm that the inputs are correct and accurate before submitting!' })

  if (confirm.isConfirmed) {
    try {
      pop.load()

      const res = await item.create(form)
      pop.success(res.message ?? 'Item added succesfully!')
      return router.replace({ name: 'librarian.collections.catalog' })
    } catch (e: any) {
      throw e
    }
  }
}
</script>

<style scoped></style>
