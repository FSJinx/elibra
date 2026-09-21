<template>
  <label :for="id" :id="id + 'Label'" class="max-w-max font-medium">
    <slot></slot>
    <span v-if="required || control?.required" class="text-danger">*</span>
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

const control = inject<any>('control')
</script>

<style scoped></style>
