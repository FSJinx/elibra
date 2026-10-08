<template>
  <div class="relative flex gap-3 p-5 w-full max-w-7xl mx-auto">
    <Form class="flex flex-col gap-5" @submit="submit">
      <SectionHeader class="bg-background border border-border rounded-xl pb-5" title="New Item" description="Please fill all required fields and re-check your inputs before submitting.">
        <div class="flex items-center justify-end gap-2">
          <Button type="button" :disabled="!hasInputs" @click="saveDraft">Save as Draft</Button>
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

      <Card class="sticky bottom-0 z-100">
        <CardBody class="flex justify-end gap-2">
          <Button as="link" :to="{ name: 'librarian.collections.catalog' }" variant="danger" data-title="Return to catalog ">Cancel</Button>
          <Button type="submit" variant="primary" :disabled="!hasInputs">Create</Button>
        </CardBody>
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
const drafts = draftStore()
const route = useRoute()

const form = reactive(emptyForm())
const errors = reactive<Record<string, unknown>>({})
const hasInputs = computed(() => {
  return Object.entries(form).some(([field, value]) => {
    if (field === 'library_id' || field === 'released') return false
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

      try {
        const res = await item.create(form)

        if (route.query.acquisition) {
          try {
            const acq = await useAcquisitionLinesStore().create({ item_id: res.data.id, acquisition_id: route.query.acquisition_id, quantity: 1 })
            pop.success(acq.message)
            return router.back()
          } catch (e: any) {
            pop.error(e.response.message)
            throw e
          }
        } else {
          pop.success(res.message ?? 'Item added succesfully!')
          return router.replace({ name: 'librarian.collections.catalog' })
        }
      } catch (e: any) {
        throw e
      }
    } catch (e: any) {
      throw e
    }
  }
}

async function saveDraft() {
  const confirm = await pop.confirm({ text: 'Save your progress to draft' })
  if (confirm.isConfirmed) {
    pop.load()
    drafts.saveItem(form)

    pop.success('Progress saved in draft')
    router.replace({ name: 'librarian.collections.catalog' })
  }
}

onMounted(() => {
  if (route.query.draft_id) {
    const draft = drafts.data.find((i) => i.id === route.query.draft_id)
    Object.assign(form, draft?.data)
  }
})
</script>
