<template>
  <div class="flex w-full flex-col gap-2">
    <!-- Input Container -->
    <div class="relative inline-flex shrink-0 items-center bg-slate-50 w-full border transition-colors min-w-20 rounded-md overflow-hidden focus-within:ring-4" :class="[sizeClass, error && error?.length > 0 ? 'border-danger focus-within:border-danger focus-within:ring-danger/20' : 'border-border focus-within:ring-success/25 focus-within:border-primary/50', { 'opacity-60 cursor-not-allowed': disabled }]">
      <!-- Slot for Prefix Icon (Optional) -->
      <span class="absolute left-3 h-full place-content-center" data-title="Show Password" v-if="leftIcon" type="button" size="small">
        <Icon :icon="leftIcon" />
      </span>

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
        :class="[inputPadding]"
        class="h-full w-full bg-transparent autofill:bg-primary transition-all duration-150 focus:outline-none disabled:cursor-not-allowed"
      />

      <!-- Toggle Password Button -->
      <span class="h-full place-content-center py-2 px-3 cursor-pointer border-l border-border" data-title="Show Password" v-if="type === 'password' && !disabled" type="button" size="small" :aria-label="show ? 'Hide password' : 'Show password'" @click="show = !show">
        <Icon variant="default-hover" :icon="show ? 'eye' : 'eye-slash'" :name="show ? 'Hide Password' : 'Show Password'" />
      </span>

      <!-- Clear Button -->
      <span class="h-full place-content-center p-2 px-3 cursor-pointer border-l border-border" v-if="enableClear && hasValue && !disabled" type="button" size="small" aria-label="Clear input" @click="model = ''">
        <Icon icon="x" name="Clear Input" />
      </span>

      <!-- Slot for Suffix Icon (Optional) -->
      <span class="h-full place-content-center py-2 pr-3" data-title="Show Password" v-if="rightIcon" type="button" size="small">
        <Icon :icon="rightIcon" />
      </span>
    </div>

    <!-- Info / Caps Lock Message -->
    <div v-if="isCapsLockOn && checkcapslock" class="flex text-warning items-center gap-1 text-[0.85rem] wrap-break-word">
      <Icon icon="info-circle" />
      <span>Caps Lock is on</span>
    </div>

    <div v-if="helper" class="flex text-info items-center gap-1 text-[0.85rem] wrap-break-word">
      <Icon icon="info-circle" />
      <span>{{ helper }}</span>
    </div>

    <!-- Error Message -->
    <p v-if="error && error.length > 0" class="text-danger text-sm">
      {{ error }}
    </p>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

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

  // Others
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
  checkCapsLock: false,
  enableClear: false,
  size: 'md',
})

const model = defineModel<string | number>({ default: '' })
const show = ref(false)
const isCapsLockOn = ref(false)
const input = ref<HTMLInputElement | null>(null)

// Safe value check
const hasValue = computed(() => String(model.value ?? '').length > 0)

const sizes: Record<Sizes, string> = {
  xs: 'text-xs h-6',
  sm: 'text-sm h-8',
  md: 'text-base h-10',
  lg: 'text-lg h-12',
  xl: 'text-xl h-14',
}

const sizeClass = computed(() => sizes[props.size])

// Dynamic input type (password toggle & custom types mapping)
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
  if (props.checkcapslock && 'getModifierState' in e && typeof (e as KeyboardEvent).getModifierState === 'function') {
    isCapsLockOn.value = (e as KeyboardEvent).getModifierState('CapsLock')
  }
}

const resetCapsLock = () => {
  isCapsLockOn.value = false
}

const inputPadding = computed(() => {
  const padding = ['py']
  if (props.leftIcon || props.rightIcon) {
    padding.push('px-5')
  } else {
    padding.push('px-3')
  }

  return padding
})

onMounted(() => {
  if (props.autoFocus) {
    input.value?.focus()
  }
})
</script>

<style scoped></style>
