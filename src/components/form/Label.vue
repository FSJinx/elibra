<template>
  <label :for="id" :id="id + 'Label'" class="max-w-max font-medium">
    <slot></slot>
    <span v-if="required" class="text-danger">*</span>
  </label>
</template>

<script setup lang="ts">
interface Props {
  id: string
  required?: boolean
}
withDefaults(defineProps<Props>(), {
  required: false,
})
</script>

<style scoped></style>
