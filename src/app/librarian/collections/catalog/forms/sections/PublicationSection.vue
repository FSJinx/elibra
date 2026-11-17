<template>
  <Card class="">
    <CardHeader>
      <h2 class="text-xl font-semibold text-primary">Publication Information</h2>
      <p class="mt-0.5 text-sm text-foreground-secondary">Publication details of the item.</p>
    </CardHeader>

    <CardBody class="space-y-5">
      <!-- Publisher-->
      <Control id="item-publication_year">
        <Label>Publisher</Label>
        <div class="flex items-center gap-2">
          <Input v-if="form.publisher" type="text" placeholder="Enter year of publication" min="1000" :max="currentYear" :model-value="form.publisher?.name" readonly />
          <AddPublisher v-model="form.publisher" :disabled="!editing" />
        </div>
      </Control>

      <!-- Publication Year -->
      <Control id="item-publication_year">
        <Label>Year of Publication</Label>
        <Input type="number" placeholder="Enter year of publication" min="1000" :max="currentYear" v-model="form.publication_year" :readonly="!editing" />
      </Control>

      <!-- Place of Publication -->
      <Control id="item-place_of_publication">
        <Label>Place of Publication</Label>
        <Input type="text" placeholder="Enter place of publication" v-model="form.place_of_publication" :readonly="!editing" />
      </Control>
    </CardBody>
  </Card>
</template>

<script setup lang="ts">
import AddPublisher from '@/app/librarian/collections/catalog/forms/sections/modals/AddPublisher.vue'

const props = withDefaults(defineProps<{ editing?: boolean; errors?: any }>(), {
  editing: true,
})
const form = defineModel<StoreItem>({ default: {} })

const currentYear = new Date().getFullYear()

watch(
  () => form.value?.publisher,
  (val) => {
    form.value.publisher_id = val?.id
    console.log('Publisher Id which was', form.value.publisher_id)
    console.log('Was set to', val?.id)
  },
  { deep: true },
)
</script>

<style scoped></style>
