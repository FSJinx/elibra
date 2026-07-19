<template>
  <span class="font-bold px-2 py-0.5 rounded-md" :class="[chipDesign]"><slot /></span>
</template>

<script setup lang="ts">
type Colors = 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'info'

interface Props {
  color?: Colors
}

const props = withDefaults(defineProps<Props>(), {
  color: 'primary',
})

const chipDesign = computed(() => {
  const designs: Record<Colors, string> = {
    primary: 'text-primary bg-primary/10',
    secondary: 'text-secondary bg-secondary/10',
    success: 'text-success bg-success/10',
    danger: 'text-danger bg-danger/10',
    warning: 'text-warning bg-warning/10',
    info: 'text-info bg-info/10',
  }

  return designs[props.color]
})
</script>

<style scoped></style>
