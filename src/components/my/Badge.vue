<template>
  <span class="inline-flex shrink-0 text-xs px-3 py-1 leading-normal rounded-lg font-medium" :class="[badgeClass]">
    <slot />
  </span>
</template>
<script setup lang="ts">

type Border = true | false

interface Props {
  border?: Border
  variant?: Variants
}

const props = withDefaults(defineProps<Props>(), {
  border: false,
  variant: 'default',
})

const badgeClass = computed(() => {
  const variants: Record<Variants, string> = {
    danger: 'border border-danger/25 bg-danger-soft text-danger-soft-foreground',
    default: 'border border-default/25 bg-default-soft text-default-soft-foreground',
    info: 'border border-info/25 bg-info-soft text-info-soft-foreground',
    primary: 'border border-primary/25 bg-primary-soft text-primary-soft-foreground',
    restore: 'border border-restore/25 bg-restore-soft text-restore-soft-foreground',
    success: 'border border-success/25 bg-success-soft text-success-soft-foreground',
    warning: 'border border-warning/25 bg-warning-soft text-warning-soft-foreground',
    text: 'bg-text-soft text-text-soft-foreground',
  }

  return variants[props.variant]
})
</script>
