<template>
  <component :is="as" class="btn relative inline-flex items-center justify-center rounded-md py-2 px-3 font-semibold transition-all duration-100" :class="[btnClass]" :disabled="disabled" @mouseup="($event.currentTarget as HTMLButtonElement).blur()">
    <Spinner class="absolute" v-if="loading" />

    <Icon :icon="leftIcon" v-if="leftIcon" :class="['mr-1.5', props.loading && 'invisible']" />
    <span :class="[props.loading && 'invisible']"> <slot /></span>
    <Icon :icon="rightIcon" v-if="rightIcon" :class="['ml-1.5', props.loading && 'invisible']" />
  </component>
</template>

<script setup lang="ts">
interface Props {
  leftIcon?: string
  rightIcon?: string
  loading?: boolean
  as?: 'button' | 'router-link'
  variant?: Variants
  disabled?: boolean
  size?: Sizes
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  as: 'button',
  variant: 'text',
  disabled: false,
  size: 'default',
})

const btnClass = computed(() => {
  const variants: Record<Variants, string> = {
    primary: 'border border-primary bg-primary/90 text-white hover:bg-primary',
    secondary: 'border-transparent bg-secondary/5 text-secondary/75 hover:bg-secondary/10',
    info: 'border-info bg-info/90 text-white hover:bg-info',
    success: 'border-success bg-success/90 text-white hover:bg-success',
    danger: 'border-danger bg-danger/90 text-white hover:bg-danger',
    warning: 'border-warning bg-warning/90 text-white hover:bg-warning',
    restore: 'border-restore bg-restore/90 text-white hover:bg-restore',
    text: '',
  }

  const focus: Record<Variants, string> = {
    primary: ' focus:ring-4 focus:ring-primary/25',
    secondary: ' focus:ring-4 focus:ring-secondary/15',
    info: ' focus:ring-4 focus:ring-info/25',
    success: ' focus:ring-4 focus:ring-success/25',
    danger: ' focus:ring-4 focus:ring-danger/25',
    warning: ' focus:ring-4 focus:ring-warning/25',
    restore: ' focus:ring-4 focus:ring-restore/25',
    text: 'focus:text-primary ',
  }

  const border = props.variant === 'text' ? 'border-transparent' : 'border'

  const disabled = props.disabled ? 'opacity-75 cursor-not-allowed' : 'cursor-pointer'
  const design = props.disabled ? 'border-transparent bg-secondary/5 text-secondary/50' : variants[props.variant ?? 'text']

  return [sizes[props.size], focus[props.variant], design, disabled, border]
})
</script>

<style scoped>
.btn {
  outline: none;
}
</style>
