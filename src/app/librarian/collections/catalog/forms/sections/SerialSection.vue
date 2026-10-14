<template>
  <Card class="rounded-none">
    <CardHeader>
      <h2 class="text-xl font-semibold text-foreground">Serial Information</h2>
      <p class="mt-0.5 text-sm text-foreground-secondary">Please fill this field if the item you're cataloging comes in series</p>
    </CardHeader>

    <CardBody class="space-y-5">
      <Control id="item-edition">
        <Label>Edition</Label>
        <Input v-model="form.edition" type="text" placeholder="Enter edition..." :error="getError(errors, 'edition')" />
      </Control>
      <Control id="item-volume">
        <Label>Volume</Label>
        <Input v-model="form.volume" type="text" placeholder="Enter volume..." :error="getError(errors, 'volume')" />
      </Control>
      <Control id="item-issue">
        <Label>Issue</Label>
        <Input v-model="form.issue" type="text" placeholder="Enter issue..." :error="getError(errors, 'issue')" />
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
</script>
