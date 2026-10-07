<template>
  <label :for="id ?? control.id" :id="(id ?? control.id) + 'Label'" class="Label max-w-max font-medium mt-2">
    <span>
      <slot></slot>
    </span>

    <span v-if="required || control?.required" class="text-danger font-medium">*</span>
    <span v-else-if="nullable || control?.nullable" class="text-muted-foreground font-normal ml-1 text-sm">(Optional)</span>
  </label>
</template>

<script setup lang="ts">
interface Props {
  id?: string
  required?: boolean
  nullable?: boolean
}

withDefaults(defineProps<Props>(), {
  required: false,
  nullable: false,
})

const control = inject<any>('control', null)
</script>

<style scoped></style>
