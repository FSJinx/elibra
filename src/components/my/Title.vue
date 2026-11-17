<template>
  <component :is="`h${level}`" :class="[titleClass]">
    <slot />
  </component>
</template>

<script setup lang="ts">
interface Props {
  level?: 1 | 2 | 3 | 4 | 5 | 6
}

const props = withDefaults(defineProps<Props>(), {
  level: 4,
})

const titleClass = computed(() => {
  const sizes: Record<any, string> = {
    1: 'text-2xl',
    2: 'text-xl',
    3: 'text-lg',
    4: 'text-md',
    5: 'text-sm',
    6: 'text-xs',
  }

  return [sizes[props.level], 'tracking-tight font-semibold']
})
</script>

<style scoped></style>
