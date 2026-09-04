<template>
  <span class="inline-flex items-center justify-center font-medium text-sm h-9 px-3 gap-3 rounded-full border border-current/30" :class="[chipDesign]">
    <span class="">
      <slot />
    </span>

    <span class="cursor-pointer" @click="$emit('remove')" v-if="removable">
      <Icon icon="x-circle" />
    </span>
  </span>
</template>

<script setup lang="ts">
type Variants = 'primary' | 'default' | 'success' | 'danger' | 'warning' | 'info' | 'restore'

interface Props {
  variant?: Variants
  removable?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  removable: false,
})

const emit = defineEmits(['remove'])

const chipDesign = computed(() => {
  const designs: Record<Variants, string> = {
    default: '',
    primary: 'text-primary-soft-foreground bg-primary-soft/25',
    success: 'text-success-soft-foreground bg-success-soft/25',
    danger: 'text-danger-soft-foreground bg-danger-soft/25',
    warning: 'text-warning-soft-foreground bg-warning-soft/25',
    info: 'text-info-soft-foreground bg-info-soft/25',
    restore: 'text-restore-soft-foreground bg-restore-soft/25',
  }

  return designs[props.variant]
})
</script>

<style scoped></style>
