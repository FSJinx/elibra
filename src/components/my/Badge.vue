<template>
  <span class="inline-flex shrink-0 text-xs p-2 py-1 leading-normal rounded-xl" :class="[badgeClass]">
    <slot />
  </span>
</template>
<script setup lang="ts">
import { computed, ref } from 'vue'

type Border = true | false
type Variant = Variants

interface Props {
  border?: Border
  variant?: Variant
}

const props = withDefaults(defineProps<Props>(), {
  border: false,
  variant: 'default',
})

const badgeClass = computed(() => {
  const variants: Record<Variant, string> = {
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
