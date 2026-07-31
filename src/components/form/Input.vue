<template>
  <div class="form-input w-full my-2" :class="[positions.parent[labelPosition], { 'opacity-60 cursor-not-allowed': disabled }]" @focusin="active = true" @focusout="active = false" :title="required ? (label ? `${label} is required.` : 'Required field.') : `${label || ''} Input`">
    <!-- Label -->
    <label v-if="label" :for="id" class="text-md shrink-0 transition-all duration-150" :class="[positions.label[labelPosition], { 'label-floating': (active || hasValue) && labelPosition === 'float' }, error ? 'text-danger' : '']">
      <span>{{ label }}</span>
      <span v-if="required" class="text-danger">*</span>
    </label>

    <!-- Input Container -->
    <div class="flex flex-col w-full gap-1">
      <div class="relative flex shrink min-w-50 items-center bg-background/50 w-full border transition-colors h-10 overflow-hidden focus-within:ring-4" :class="[radiusClass, error ? 'border-danger focus-within:border-danger focus-within:ring-danger/20' : 'border-border focus-within:ring-primary-soft focus-within:border-primary/50']">
        <!-- Slot para sa Prefix Icon (Optional) -->
        <slot name="prefix" />

        <input :id="id" :name="id" v-model="model" :type="inputType" :placeholder="inputPlaceholder" :required="required" :disabled="disabled" :autocomplete="autocomplete" class="px-4 w-full h-full bg-transparent transition-all duration-150 focus:outline-none disabled:cursor-not-allowed" />

        <!-- Clear Button -->
        <Button v-if="enableClear && hasValue && !disabled" type="button" size="small" aria-label="Clear input" @click="model = ''">
          <Icon icon="X" name="Clear Input" />
        </Button>

        <!-- Toggle Password Button -->
        <Button v-if="type === 'password' && !disabled" type="button" size="small" :aria-label="show ? 'Hide password' : 'Show password'" @click="show = !show">
          <Icon variant="default-hover" :icon="show ? 'Eye' : 'EyeClosed'" :name="show ? 'Hide Password' : 'Show Password'" />
        </Button>

        <!-- Slot para sa Suffix Icon (Optional) -->
        <slot name="suffix" />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

type Types = 'text' | 'number' | 'password' | 'email' | 'tel' | 'username' | 'hidden'
type Autocomplete = 'on' | 'off' | string
type LabelPosition = 'float' | 'default' | 'top'
type Radius = 'none' | 'sm' | 'md' | 'lg' | 'xl' | 'full'

interface Props {
  id: string
  label?: string
  type?: Types
  radius?: Radius
  autocomplete?: Autocomplete
  placeholder?: string
  labelPosition?: LabelPosition
  required?: boolean
  disabled?: boolean
  enableClear?: boolean
  error?: boolean
  errorMessage?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  radius: 'xl',
  autocomplete: 'off',
  labelPosition: 'default',
  required: false,
  disabled: false,
  enableClear: false,
  error: false,
  errorMessage: '',
})

const model = defineModel<string | number>({ default: '' })
const active = ref(false)
const show = ref(false)

// Safe value check
const hasValue = computed(() => String(model.value ?? '').length > 0)

// Dynamic input type (password toggle)
const inputType = computed(() => {
  if (props.type === 'password') {
    return show.value ? 'text' : 'password'
  }
  return props.type
})

// Dynamic border radius mapper
const radiusClass = computed(() => {
  const map: Record<Radius, string> = {
    none: 'rounded-none',
    sm: 'rounded-sm',
    md: 'rounded-md',
    lg: 'rounded-lg',
    xl: 'rounded-xl',
    full: 'rounded-full',
  }
  return map[props.radius] ?? 'rounded-xl'
})

const positions = computed(() => ({
  parent: {
    float: `relative inline-flex items-center ${props.label ? 'py-2' : ''}`,
    default: 'inline-flex items-center gap-5',
    top: 'inline-flex flex-col gap-2.5',
  },
  label: {
    float: 'absolute label-float left-0 pl-4 z-1 text-slate-500 pointer-events-none',
    default: 'w-35 line-clamp-1',
    top: '',
  },
}))

const inputPlaceholder = computed(() => {
  if (props.labelPosition === 'float') {
    if (active.value) {
      return props.placeholder ?? props.label ?? ''
    }
    return !props.label ? (props.placeholder ?? '') : ''
  }
  return props.placeholder ?? props.label ?? ''
})
</script>

<style scoped>
.label-floating {
  transform: translate(-0.5em, -2.4em);
  font-size: var(--text-sm, 0.875rem);
  transition: all 0.2s ease-in-out;
}

.form-input:focus-within label,
.form-input:hover label {
  color: var(--color-primary, inherit);
}
</style>
