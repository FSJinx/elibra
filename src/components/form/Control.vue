<template>
  <div class="control" :class="controlClass">
    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  direction?: 'row' | 'col'
  required?: boolean

  col?: boolean
  row?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  direction: 'row',

  col: false,
  row: true,
})

const direction = computed(() => {
  if (props.direction === 'col' || props.col) {
    return 'grid-cols-1 items-start'
  } else if (props.direction === 'row' || props.row) {
    return 'grid-cols-2 items-start'
  }
})

const controlClass = computed(() => {
  const baseDesign = 'grid gap-2 group'

  return [baseDesign, direction.value]
})

provide('control', {
  required: props.required,
})
</script>

<style scoped></style>
