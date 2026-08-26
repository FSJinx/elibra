<template>
  <component :is="buttonAs" :type="type" class="relative shrink-0 inline-flex items-center font-medium rounded-md gap-3 px-3.5 transition-all duration-100 outline-none tracking-tight leading-0" :class="[btnClass, sizeClass]" :disabled="disabled" @mouseup="($event.currentTarget as HTMLButtonElement).blur()" @click="$emit('click')">
    <Spinner class="absolute" v-if="loading" />

    <Icon :icon="leftIcon" v-if="leftIcon && leftIcon.length > 0" :class="[loading && 'invisible']" />

    <Icon :icon="icon" v-if="icon && icon.length > 0" :class="[loading && 'invisible']" />
    <span v-if="$slots.default?.() && $slots.default?.().length > 0" class="inline-flex flex-1 items-center" :class="[align[props.align], loading && 'invisible']">
      <slot />
    </span>
    <Icon :icon="rightIcon" v-if="rightIcon && rightIcon.length > 0" :class="[loading && 'invisible']" />
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
  align?: 'left' | 'center' | 'right'
}

const props = withDefaults(defineProps<Props>(), {
  loading: false,
  as: 'button',
  variant: 'default',
  disabled: false,
  size: 'md',
  type: 'button',
  align: 'center',
})

const sizes: Record<Sizes, string> = {
  xs: 'text-xs h-8',
  sm: 'text-sm h-10',
  md: 'text-base h-11',
  lg: 'text-lg h-14',
  xl: 'text-xl h-18',
}

const sizeClass = computed(() => sizes[props.size])

const buttonAs = computed(() => {
  const asses: Record<Ases, string> = {
    button: 'button',
    link: 'router-link',
  }

  return asses[props.as]
})

const align: Record<string, string> = {
  left: 'justify-start',
  center: 'justify-center',
  right: 'justify-end',
}

const btnClass = computed(() => {
  const variants: Record<Variants, string> = {
    primary: 'border-primary bg-primary text-primary-foreground hover:bg-primary-hover',
    default: 'border-border bg-background text-default-foreground',
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
    text: '',
  }

  const border = props.variant === 'text' ? '' : 'border'

  const disabled = props.disabled || props.loading ? 'opacity-75 cursor-not-allowed' : 'cursor-pointer'
  const design = variants[props.variant ?? 'text']

  return [focus[props.variant], design, disabled, border, align[props.align]]
})

defineEmits(['click'])
</script>

<style scoped></style>
