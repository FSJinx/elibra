<template>
  <component :is="buttonAs" :type="type" class="btn relative inline-flex items-center justify-center rounded-xl min-h-10 py-2 px-3 font-medium transition-all duration-100" :class="[btnClass]" :disabled="disabled || loading" @mouseup="($event.currentTarget as HTMLButtonElement).blur()">
    <Spinner class="absolute" v-if="loading" />

    <Icon :icon="leftIcon" v-if="leftIcon" :class="['mr-1.5', props.loading && 'invisible']" />
    <span class="flex justify-center items-center gap-1.5" :class="[props.loading && 'invisible']"><slot /></span>
    <Icon :icon="rightIcon" v-if="rightIcon" :class="['ml-1.5', props.loading && 'invisible']" />
  </component>
</template>

<script setup lang="ts">
type Types = 'submit' | 'button'
type Ases = 'button' | 'link'

interface Props {
  leftIcon?: string
  rightIcon?: string
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
  variant: 'text',
  disabled: false,
  size: 'default',
  type: 'button',
})

const buttonAs = computed(() => {
  const asses: Record<Ases, string> = {
    button: 'button',
    link: 'router-link',
  }

  return asses[props.as]
})

const btnClass = computed(() => {
  const variants: Record<Variants, string> = {
    primary: 'border border-primary bg-primary text-primary-foreground hover:bg-primary-hover',
    secondary: 'border-transparent bg-secondary text-secondary-foreground hover:bg-secondary-hover',
    info: 'border-info bg-info text-info-foreground hover:bg-info-hover',
    success: 'border-success bg-success text-success-foreground hover:bg-success-hover',
    danger: 'border-danger bg-danger text-danger-foreground hover:bg-danger-hover',
    warning: 'border-warning bg-warning text-warning-foreground hover:bg-warning-hover',
    restore: 'border-restore bg-restore text-restore-foreground hover:bg-restore-hover',
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

  const disabled = props.disabled || props.loading ? 'opacity-75 cursor-not-allowed' : 'cursor-pointer'
  const design = props.disabled ? 'border-transparent bg-muted text-muted-foreground' : variants[props.variant ?? 'text']

  return [sizes[props.size], focus[props.variant], design, disabled, border]
})
</script>

<style scoped>
.btn {
  outline: none;
}
</style>
