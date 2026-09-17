<template>
  <!-- <Badge :variant="variant">
    <span class="size-px" :class="statusIndicator"></span>
    <slot />
  </Badge> -->

  <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-medium tracking-wide" :class="[statusIndicator]">
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

const parse = useParser()

const statusIndicator = computed(() => {
  const shades: Record<string, string> = {
    success: 'bg-success-soft text-success-soft-foreground',
    danger: 'bg-danger/5 text-danger-soft-foreground',
    warning: 'bg-warning/5 text-warning-soft-foreground',
    restore: 'bg-restore/5 text-restore-soft-foreground',
    info: 'bg-info/5 text-info-soft-foreground',
    default: 'bg-default/25 ',
  }

  return shades[parse.status(props?.variant) ?? 'default']
})
</script>

<style scoped></style>
