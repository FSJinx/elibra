<template>
  <Badge :variant="variant">
    <span class="size-px" :class="statusIndicator"></span>
    <slot />
  </Badge>
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
    success: 'bg-primary',
    danger: 'bg-danger',
    warning: 'bg-warning',
    restore: 'bg-restore',
    info: 'bg-info',
    default: 'bg-default',
  }

  return shades[props?.variant ?? 'default']
})
</script>

<style scoped></style>
