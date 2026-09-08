<template>
  <div class="control" :class="controlClass">
    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  direction?: 'row' | 'col'
}

const props = withDefaults(defineProps<Props>(), {
  direction: 'row',
})

const controlClass = computed(() => {
  const baseDesign = 'grid gap-2 group'

  const direction = props.direction === 'row' ? 'grid-cols-2 items-center' : 'grid-cols-1'

  return [baseDesign, direction]
})
</script>

<style scoped></style>
