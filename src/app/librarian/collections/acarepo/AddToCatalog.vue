<template>
  <div class="relative gap-3 size-full max-w-7xl mx-auto">
    <Form class="flex flex-col gap-5 p-5" @submit="submit">
      <SectionHeader class="bg-background border border-border rounded-xl pb-5" :title="`Currently Cataloging: ${acarepo.currentData?.title}`" description="Review submission's information before adding to catalog" icon=""></SectionHeader>

      <!-- BASIC INFORMATION -->
      <BaseSection v-model="form" :errors="errors" />

      <!-- PUBLICATION INFORMATION -->
      <PublicationSection v-model="form" :errors="errors" />

      <!-- PHYSICAL DESCRIPTION INFORMATION -->
      <PhysicalDescriptionSection v-model="form" :errors="errors" />

      <!-- PHYSICAL DESCRIPTION INFORMATION -->
      <SerialSection v-model="form" :errors="errors" />

      <!-- AUTHOR CONTROL INFORMATION -->
      <AuthorityControlSection v-model="form" :errors="errors" />

      <!-- AUTHOR CONTROL INFORMATION -->
      <AuthorSection v-model="form" :editing="true" :errors="errors" />

      <Card class="sticky bottom-0 z-100">
        <CardBody class="flex justify-end gap-2">
          <PreviewDrawer :data="acarepo.currentData" />

          <Button @click="router.back()" variant="danger" data-title="Return">Cancel</Button>
          <Button type="submit" variant="primary">Create</Button>
        </CardBody>
      </Card>
    </Form>
  </div>
</template>

<script setup lang="ts">
import PreviewDrawer from '@/app/librarian/collections/acarepo/drawer/PreviewDrawer.vue'
import AuthorityControlSection from '@/app/librarian/collections/catalog/forms/sections/AuthorityControlSection.vue'
import AuthorSection from '@/app/librarian/collections/catalog/forms/sections/AuthorSection.vue'
import BaseSection from '@/app/librarian/collections/catalog/forms/sections/BaseSection.vue'
import PhysicalDescriptionSection from '@/app/librarian/collections/catalog/forms/sections/PhysicalDescriptionSection.vue'
import PublicationSection from '@/app/librarian/collections/catalog/forms/sections/PublicationSection.vue'
import SerialSection from '@/app/librarian/collections/catalog/forms/sections/SerialSection.vue'

const pop = usePopup()
const acarepo = acaRepoStore()
const item = itemStore()
const options = optionStore()

const defaultForm = (): StoreItem => ({
  title: acarepo.currentData?.title ?? '',
  description: acarepo.currentData?.description ?? '',
  doi: acarepo.currentData?.doi ?? '',
  electronic_file: acarepo.currentData?.electronic_file ?? null,
  keywords: acarepo.currentData?.keywords ?? [],
  language_id: acarepo.currentData?.language_id ?? '',
  item_type_id: options.item_types?.find((i) => i.slug === 'thesis')?.id,
  item_type_category_id: options.categories?.find((i) => i.code === acarepo.currentData?.category)?.id,
})
const form = ref<StoreItem>(defaultForm())
const errors = ref<StoreItem>()

async function submit() {
  const confirm = await pop.confirm({ text: 'Please confirm that the inputs are correct and accurate before submitting!' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await acarepo.catalog(acarepo.currentData, form.value)
      pop.success(res.message)
      router.back()
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>

<style scoped></style>
