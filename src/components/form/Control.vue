<template>
  <div :class="controlClass">
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
  const baseDesign = 'flex gap-2 group'

  const direction = props.direction === 'row' ? 'flex-row items-center' : 'flex-col'

  return [baseDesign, direction]
})
</script>

<style scoped></style>
