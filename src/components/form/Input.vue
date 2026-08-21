<template>
  <div class="flex w-full flex-col gap-1.5">
    <!-- Input Container -->
    <div class="group relative flex w-full items-center bg-background border transition-all duration-150 min-w-20 rounded-md overflow-hidden focus-within:ring-4" :class="[sizeConfig.container, error && error.length > 0 ? 'border-danger focus-within:border-danger focus-within:ring-danger/20' : 'border-border focus-within:ring-success/25 focus-within:border-primary/50', { 'opacity-60 cursor-not-allowed bg-slate-100': disabled }]">
      <!-- Prefix Icon -->
      <span v-if="leftIcon" class="flex items-center justify-center shrink-0 pl-3 text-slate-400">
        <Icon :icon="leftIcon" />
      </span>

      <!-- Native Input -->
      <input
        ref="input"
        :id="id"
        :name="id"
        v-model="model"
        :type="inputType"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :readonly="readonly"
        :min="min"
        :max="max"
        :pattern="pattern"
        :autocomplete="autocomplete"
        :spellcheck="spellcheck"
        @focus="checkCapsLock"
        @blur="resetCapsLock"
        @click="checkCapsLock"
        @keydown="checkCapsLock"
        @keyup="checkCapsLock"
        class="h-full w-full flex-1 bg-transparent text-slate-900 transition-all duration-150 focus:outline-none disabled:cursor-not-allowed autofill:bg-primary"
        :class="[sizeConfig.input, leftIcon ? 'pl-2' : 'pl-3', hasSuffixActions ? 'pr-2' : 'pr-3']"
      />

      <!-- Suffix Actions Container -->
      <div v-if="hasSuffixActions" class="flex items-center h-full shrink-0">
        <!-- Toggle Password Button -->
        <button v-if="type === 'password' && !disabled" type="button" class="flex h-full items-center justify-center px-3 text-slate-500 hover:text-slate-800 focus:outline-none border-l border-border transition-colors" :aria-label="show ? 'Hide password' : 'Show password'" @click="show = !show">
          <Icon variant="default-hover" :icon="show ? 'eye' : 'eye-slash'" :name="show ? 'Hide Password' : 'Show Password'" />
        </button>

        <!-- Clear Button -->
        <button v-if="enableClear && hasValue && !disabled" type="button" class="flex h-full items-center justify-center px-3 text-slate-500 hover:text-slate-800 focus:outline-none border-l border-border transition-colors" aria-label="Clear input" @click="model = ''">
          <Icon icon="x" name="Clear Input" />
        </button>

        <!-- Suffix Icon -->
        <span v-if="rightIcon" class="flex h-full items-center justify-center pr-3 pl-2 text-slate-400">
          <Icon :icon="rightIcon" />
        </span>
      </div>
    </div>

    <!-- Caps Lock Warning -->
    <div v-if="isCapsLockOn && checkcapslock" class="flex items-center gap-1.5 text-xs text-warning">
      <Icon icon="info-circle" />
      <span>Caps Lock is on</span>
    </div>

    <!-- Helper Text -->
    <div v-if="helper" class="flex items-center gap-1.5 text-xs text-info">
      <Icon icon="info-circle" />
      <span>{{ helper }}</span>
    </div>

    <!-- Error Message -->
    <p v-if="error && error.length > 0" class="text-xs font-medium text-danger">
      {{ error }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

type Sizes = 'xs' | 'sm' | 'md' | 'lg' | 'xl'
type Types = 'text' | 'number' | 'password' | 'email' | 'tel' | 'username' | 'hidden'
type Autocomplete = 'on' | 'off' | string

interface Props {
  // Base
  id: string
  type?: Types
  placeholder?: string

  // Validation
  required?: boolean
  min?: number | string
  max?: number | string
  pattern?: string

  // Behavior
  disabled?: boolean
  readonly?: boolean
  autocomplete?: Autocomplete
  autoFocus?: boolean
  spellcheck?: boolean

  // Actions
  enableClear?: boolean
  checkcapslock?: boolean

  // Display
  leftIcon?: string
  rightIcon?: string
  error?: string
  size?: Sizes
  helper?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: '',
  autocomplete: 'off',
  required: false,
  disabled: false,
  readonly: false,
  autoFocus: false,
  checkcapslock: false,
  enableClear: false,
  size: 'md',
})

const model = defineModel<string | number>({ default: '' })
const show = ref(false)
const isCapsLockOn = ref(false)
const input = ref<HTMLInputElement | null>(null)

// Safe value check
const hasValue = computed(() => String(model.value ?? '').length > 0)

// Check if any suffix elements exist
const hasSuffixActions = computed(() => {
  return (props.type === 'password' && !props.disabled) || (props.enableClear && hasValue.value && !props.disabled) || Boolean(props.rightIcon)
})

// Container and font size configurations
const sizeMap: Record<Sizes, { container: string; input: string }> = {
  xs: { container: 'h-7', input: 'text-xs' },
  sm: { container: 'h-8', input: 'text-sm' },
  md: { container: 'h-10', input: 'text-base' },
  lg: { container: 'h-12', input: 'text-lg' },
  xl: { container: 'h-14', input: 'text-xl' },
}

const sizeConfig = computed(() => sizeMap[props.size] ?? sizeMap.md)

// Dynamic input type
const inputType = computed(() => {
  if (props.type === 'password') {
    return show.value ? 'text' : 'password'
  }
  if (props.type === 'username') {
    return 'text'
  }
  return props.type
})

const checkCapsLock = (e: Event) => {
  if (props.checkcapslock && e instanceof KeyboardEvent && typeof e.getModifierState === 'function') {
    isCapsLockOn.value = e.getModifierState('CapsLock')
  }
}

const resetCapsLock = () => {
  isCapsLockOn.value = false
}

onMounted(() => {
  if (props.autoFocus) {
    input.value?.focus()
  }
})
</script>
