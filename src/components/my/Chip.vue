<template>
  <span class="inline-flex items-center justify-center font-bold" :class="[chipDesign]"><slot /></span>
</template>

<script setup lang="ts">
type Variants = 'primary' | 'default' | 'success' | 'danger' | 'warning' | 'info' | 'restore'

interface Props {
  variant?: Variants
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
})

const chipDesign = computed(() => {
  const designs: Record<Variants, string> = {
    primary: 'text-primary-soft-foreground bg-primary-soft',
    default: '',
    success: 'text-success-soft-foreground bg-success-soft',
    danger: 'text-danger-soft-foreground bg-danger-soft',
    warning: 'text-warning-soft-foreground bg-warning-soft',
    info: 'text-info-soft-foreground bg-info-soft',
    restore: 'text-restore-soft-foreground bg-restore-soft',
  }

  return designs[props.variant]
})
</script>

<style scoped></style>
