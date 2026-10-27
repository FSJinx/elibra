<template>
  <Card class="rounded-none">
    <CardHeader>
      <h2 class="text-xl font-semibold text-primary">Physical Description</h2>
      <p class="mt-0.5 text-sm text-foreground-secondary">Physical description for this catalog record</p>
    </CardHeader>

    <CardBody class="space-y-5">
      <Control id="item-pages">
        <Label>Pages</Label>
        <Input v-model="form.pages" type="number" placeholder="Enter item's page count here..." :readonly="!editing" />
      </Control>

      <Control id="item-department_id">
        <Label>
          Department
          <p class="text-info text-sm mt-2">
            <Icon icon="info-circle" />
            Fill this field only if the item is an academic thesis and is published by your campus.
          </p>
        </Label>
        <Select id="item-department_id" v-model="form.department_id" :error="getError(errors, 'department_id')" :disabled="!editing">
          <Option value="" selected disabled>Select a department</Option>
          <Option v-for="department in options.departments?.filter((i) => i.campus_id == auth.user?.campus_id)" :key="department.id" :value="department.id">{{ department.name }}</Option>
        </Select>
      </Control>

      <Control id="item-doi">
        <Label>DOI</Label>
        <Input v-model="form.doi" type="text" placeholder="Enter DOI..." :error="getError(errors, 'doi')" :readonly="!editing" />
      </Control>
    </CardBody>
  </Card>
</template>

<script setup lang="ts">
import { getError, type Form } from '@/app/librarian/collections/catalog/forms/form'

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
const auth = authStore()
</script>
