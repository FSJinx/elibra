<template>
  <Card class="rounded-none">
    <CardHeader>
      <h2 class="text-xl font-semibold text-foreground">Physical Description</h2>
      <p class="mt-0.5 text-sm text-foreground-secondary">Physical description for this catalog record</p>
    </CardHeader>

    <CardBody class="space-y-5">
      <Control id="item-pages">
        <Label>Pages</Label>
        <Input v-model="form.pages" type="number" placeholder="Enter item's page count here..." />
      </Control>

      <Control id="item-department_id">
        <Label>
          Department
          <p class="text-info text-sm mt-2">
            <Icon icon="info-circle" />
            Fill this field only if the item is an academic thesis and is published by your campus.
          </p>
        </Label>
        <Select id="item-department_id" v-model="form.department_id" :error="getError(errors, 'department_id')">
          <Option value="" disabled>Select a department</Option>
          <Option v-for="department in departments.data" :key="department.id" :value="department.id">{{ department.name }}</Option>
        </Select>
      </Control>

      <Control id="item-doi">
        <Label>DOI</Label>
        <Input v-model="form.doi" type="text" placeholder="Enter DOI..." :error="getError(errors, 'doi')" />
      </Control>
    </CardBody>
  </Card>
</template>

<script setup lang="ts">
import { getError, type Form } from '@/app/librarian/collections/catalog/forms/form'

defineProps<{
  errors: any
}>()

const form = defineModel<Partial<Form>>({ default: {} })
const departments = useDepartmentStore()
</script>
