<template>
  <input :id="id" :name="id" :type="inputType" :class="inputClass" :placeholder="placeholder" :required="required" :min="min" :max="max" :pattern="pattern" :disabled="disabled" :readonly="readonly" :autofocus="autofocus" :autocomplete="autocomplete" :spellcheck="spellcheck" />
  <p class="ml-0.5 text-danger text-sm tracking-wide capitalize" v-if="error && error.length > 0">{{ error }}</p>
</template>

<script setup lang="ts">
type Types = 'text' | 'number' | 'password' | 'email' | 'tel' | 'username' | 'hidden'

interface Props {
  // Basic
  id: string
  type?: Types
  class?: string
  placeholder: string

  // Validation
  required?: boolean
  min?: number
  max?: number
  pattern?: string

  // Behavior
  disabled?: boolean
  readonly?: boolean
  autofocus?: boolean
  autocomplete?: 'on' | 'off' | string
  spellcheck?: boolean

  //   Others
  error?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'text',
  placeholder: '',
  required: false,
  disabled: false,
  readonly: false,
  autofocus: false,
  autocomplete: 'off',
})

// Maps custom types (like 'username') to valid standard HTML input types ('text')
const inputType = computed(() => {
  return props.type === 'username' ? 'text' : props.type
})

const inputClass = computed(() => {
  const baseClass = 'py-2.5 px-4 rounded-md border border-primary bg-slate-50 h-max w-full'
  const focusClass = 'focus-within:ring-4 focus-within:ring-success/30 focus-within:border-primary'

  return [baseClass, focusClass, props.class]
})
</script>

<style scoped></style>
