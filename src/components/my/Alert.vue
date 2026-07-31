<template>
  <div class="border text-sm px-3 py-2 rounded-lg my-1" :class="alertClass">
    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  variant?: Variants
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'text',
})

const alertClass = computed(() => {
  const variants: Record<Variants, string> = {
    primary: 'bg-primary-soft text-primary-soft-foreground border-primary',
    secondary: 'bg-secondary-soft text-secondary-soft-foreground border-secondary',
    danger: 'bg-danger-soft text-danger-soft-foreground border-danger',
    info: 'bg-info-soft text-info-soft-foreground border-info',
    restore: 'bg-restore-soft text-restore-soft-foreground border-restore',
    success: 'bg-success-soft text-success-soft-foreground border-success',
    text: 'bg-transparent text-muted border-transparent',
    warning: 'bg-warning-soft text-warning-soft-foreground border-warning',
  }

  return variants[props.variant]
})
</script>

<style scoped></style>
