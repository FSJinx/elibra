<template>
  <div class="flex flex-col w-full" :class="[sizeClasses[props.size]]">
    <span class="inline mb-1" v-if="label || props.required">
      <label :for="props.name" class="text-sm text-primary font-semibold w-100">{{ label }}</label>
      <span class="text-red-500" v-if="props.required">*</span>
    </span>
    <div class="relative flex items-center w-full">
      <input :type="props.type" class="px-3 py-2 pr-10 w-full border no-spinner" :class="[inputClasses, props.required && model === '' ? 'border-red-500' : 'border-primary']" :name="props.name" :id="props.name" :placeholder="props.placeholder ?? props.label" :required="props.required" v-model="model" :autocomplete="props.autocomplete" :pattern="props.validate ? computedPattern : undefined" />
      <X v-if="model" class="h-5 w-5 z-10 absolute right-0 mr-3 text-gray-500 cursor-pointer" @click="model = ''" :title="`Clear ${label}`" />
    </div>
    <span class="flex items-center gap-1.5 text-sm text-red-500 mt-1" v-if="props.error">
      <TriangleAlert class="h-3.5 w-3.5" />
      <p>{{ props.error }}</p>
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
// Siguraduhing may import ka ng iyong mga icons gaya nito:
// import { X, TriangleAlert } from 'lucide-vue-next'

const model = defineModel<string>({ default: '' })

const props = withDefaults(
  defineProps<{
    // Label Contents
    name: string
    label?: string
    value?: string
    error?: string
    placeholder?: string

    // Input Property
    type?: 'text' | 'password' | 'email' | 'number' | 'tel'
    disable?: boolean
    required?: boolean
    validate?: boolean
    autocomplete?: 'on' | 'off' | 'username' | 'email' | (string & {})

    variant?: 'rounded' | 'circle'
    size?: 'small' | 'default' | 'large'
  }>(),
  { type: 'text', variant: 'rounded', size: 'default', validate: false },
)

const sizeClasses = { small: 'text-sm', default: 'text-base', large: 'text-lg' }
const variantClasses = { rounded: 'rounded', circle: 'rounded-full' }

// Ginawang totoong RegEx Object para madaling gamitin ang `.source` (Tinatanggal nito ang / sa simula at dulo)
const regexPatterns: Record<string, RegExp> = {
  email: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
  tel: /^(09|\+639)\d{9}$/,
  username: /^[a-zA-Z0-9_\-]{3,16}$/,
  password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/,
  number: /^\d+$/,
  text: /^[a-zA-Z\sñÑ-]+$/,
}

// Dynamics selector para sa pattern base sa type o name ng input field
const computedPattern = computed(() => {
  // Kung ang pangalan ng field ay username pero type='text', unahin natin ang username pattern
  if (props.name === 'username' || props.autocomplete === 'username') {
    return regexPatterns.username.source
  }

  return regexPatterns[props.type]?.source || ''
})

const inputClasses = computed(() => [variantClasses[props.variant], sizeClasses[props.size]])
</script>

<style scoped>
input[type='number']::-webkit-outer-spin-button,
input[type='number']::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type='number'] {
  -moz-appearance: textfield;
  appearance: textfield;
}
</style>
