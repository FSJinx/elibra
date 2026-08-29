<template>
  <!-- <Badge :variant="variant">
    <span class="size-px" :class="statusIndicator"></span>
    <slot />
  </Badge> -->

  <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-medium" :class="[statusIndicator]">
    <slot />
  </span>
</template>

<script setup lang="ts">
interface Props {
  variant?: string
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
})

const statusIndicator = computed(() => {
  const shades: Record<string, string> = {
    success: 'bg-success-soft text-success-soft-foreground',
    danger: 'bg-danger-soft text-danger-soft-foreground',
    warning: 'bg-warning-soft text-warning-soft-foreground',
    restore: 'bg-restore-soft text-restore-soft-foreground',
    info: 'bg-info-soft text-info-soft-foreground',
    default: 'bg-default-soft text-default-soft-foreground',
  }

  return shades[props?.variant ?? 'default']
})
</script>

<style scoped></style>
