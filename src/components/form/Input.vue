<template>
  <div class="form-input flex w-full flex-col gap-1">
    <!-- Input Container -->
    <div class="flex w-full">
      <div class="relative flex shrink-0 items-center bg-slate-50 w-full border transition-colors min-h-10 min-w-20 rounded-md overflow-hidden focus-within:ring-4" :class="[displayInfo && displayInfo.status === 'error' ? 'border-danger focus-within:border-danger focus-within:ring-danger/20' : 'border-border focus-within:ring-success/25 focus-within:border-primary/50', { 'opacity-60 cursor-not-allowed': disabled }]">
        <!-- Slot for Prefix Icon (Optional) -->
        <Icon :icon="leftIcon" v-if="leftIcon" class="mr-3 -ml-0.5" />

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
          class="px-4 py-2 w-full bg-transparent autofill:bg-primary transition-all duration-150 focus:outline-none disabled:cursor-not-allowed"
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
        <Icon :icon="rightIcon" v-if="rightIcon" class="ml-4 -mr-0.5" />
      </div>
    </div>

    <!-- Info / Caps Lock Message -->
    <div
      v-if="displayInfo && displayInfo.message"
      class="flex items-center gap-1 text-[0.85rem] wrap-break-word"
      :class="{
        'text-danger': displayInfo.status === 'error',
        'text-warning': displayInfo.status === 'warning',
        'text-info': displayInfo.status === 'info' || !displayInfo.status,
      }"
    >
      <Icon icon="info-circle" />
      <span>{{ displayInfo.message }}</span>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

type Types = 'text' | 'number' | 'password' | 'email' | 'tel' | 'username' | 'hidden'
type Autocomplete = 'on' | 'off' | string

interface Info {
  status: 'error' | 'warning' | 'info' | string
  message: string
}

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
  info?: Info
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
})

const model = defineModel<string | number>({ default: '' })
const show = ref(false)
const isCapsLockOn = ref(false)
const input = ref<HTMLInputElement | null>(null)

// Safe value check
const hasValue = computed(() => String(model.value ?? '').length > 0)

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

// Merge Caps Lock warning with prop info message
const displayInfo = computed(() => {
  if (isCapsLockOn.value) {
    return {
      status: 'warning',
      message: 'Caps Lock is on.',
    }
  }
  return props.info
})

const checkCapsLock = (e: Event) => {
  if (props.checkcapslock && 'getModifierState' in e && typeof (e as KeyboardEvent).getModifierState === 'function') {
    isCapsLockOn.value = (e as KeyboardEvent).getModifierState('CapsLock')
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

<style scoped></style>
