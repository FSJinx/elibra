<template>
  <component :is="buttonAs" :type="type" class="relative inline-flex items-center justify-center font-medium rounded-md gap-1.5 px-3 transition-all duration-100 outline-none tracking-tight leading-none" :class="[btnClass, sizeClass]" :disabled="disabled" @mouseup="($event.currentTarget as HTMLButtonElement).blur()">
    <Spinner class="absolute" v-if="disabled" />

    <Icon :icon="leftIcon" v-if="leftIcon" :class="[disabled && 'invisible']" />

    <Icon :icon="icon" v-if="icon" :class="[disabled && 'invisible']" />
    <span v-if="$slots.default?.() && $slots.default?.().length > 0" class="inline-flex flex-1 justify-center items-center" :class="[disabled && 'invisible']">
      <slot />
    </span>
    <Icon :icon="rightIcon" v-if="rightIcon" :class="[disabled && 'invisible']" />
  </component>
</template>

<script setup lang="ts">
type Types = 'submit' | 'button'
type Ases = 'button' | 'link'

interface Props {
  leftIcon?: string
  rightIcon?: string
  icon?: string
  loading?: boolean
  as?: Ases
  type?: Types
  variant?: Variants
  disabled?: boolean
  size?: Sizes
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  as: 'button',
  variant: 'default',
  disabled: false,
  size: 'md',
  type: 'button',
})

const sizes: Record<Sizes, string> = {
  xs: 'text-xs h-6',
  sm: 'text-sm h-8',
  md: 'text-base h-10',
  lg: 'text-lg h-12',
  xl: 'text-xl h-14',
}

const sizeClass = computed(() => sizes[props.size])

const buttonAs = computed(() => {
  const asses: Record<Ases, string> = {
    button: 'button',
    link: 'router-link',
  }

  return asses[props.as]
})

const btnClass = computed(() => {
  const variants: Record<Variants, string> = {
    primary: 'border-primary bg-primary text-primary-foreground hover:bg-primary-hover',
    default: 'border-border bg-background text-default-foreground hover:text-primary-hover hover:border-primary/50',
    info: 'border-info bg-info text-info-foreground hover:bg-info-hover',
    success: 'border-success bg-success text-success-foreground hover:bg-success-hover',
    danger: 'border-danger bg-danger text-danger-foreground hover:bg-danger-hover',
    warning: 'border-warning bg-warning text-warning-foreground hover:bg-warning-hover',
    restore: 'border-restore bg-restore text-restore-foreground hover:bg-restore-hover',
    text: '',
  }

  const focus: Record<Variants, string> = {
    primary: ' focus:ring-4 focus:ring-primary/25',
    default: ' focus:ring-4 focus:ring-default/15',
    info: ' focus:ring-4 focus:ring-info/25',
    success: ' focus:ring-4 focus:ring-success/25',
    danger: ' focus:ring-4 focus:ring-danger/25',
    warning: ' focus:ring-4 focus:ring-warning/25',
    restore: ' focus:ring-4 focus:ring-restore/25',
    text: 'focus:text-primary ',
  }

  const border = props.variant === 'text' ? '' : 'border'

  const disabled = props.disabled || props.loading ? 'opacity-75 cursor-not-allowed' : 'cursor-pointer'
  const design = variants[props.variant ?? 'text']

  return [focus[props.variant], design, disabled, border]
})
</script>

<style scoped></style>
