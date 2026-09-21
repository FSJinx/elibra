<template>
  <component :is="`h${level}`" :class="[titleClass, $attrs.class]">
    <slot />
  </component>
</template>

<script setup lang="ts">
interface Props {
  level: 1 | 2 | 3 | 4 | 5 | 6
}

const props = defineProps<Props>()

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
