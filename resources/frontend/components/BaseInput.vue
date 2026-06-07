<template>
  <div class="flex flex-col w-full" :class="[sizeClasses[props.size]]">
    <span class="inline mb-2" v-if="label || props.required">
      <label :for="props.name" class="text-sm text-primary font-semibold w-100">{{ label }}</label>
      <span class="text-red-500 text-xs italic" v-if="props.required"> (required)</span>
    </span>
    <div class="relative flex items-center w-full">
      <input :type="props.type" class="px-3.5 py-1.5 w-full border no-spinner" :class="[inputClasses, props.required && model === '' ? 'border-red-500' : 'border-primary']" :name="props.name" :id="props.name" :placeholder="props.placeholder ?? props.label" :required="props.required" v-model="model" :autocomplete="props.autocomplete" />
      <CircleX v-if="model" class="h-5 w-5 z-10 absolute right-0 mr-2 text-gray-500 cursor-pointer" @click="model = ''" :title="`Clear ${label}`" />
    </div>
    <span class="flex items-center gap-1.5 text-sm text-red-500" v-if="props.error">
      <TriangleAlert class="h-3.5 w-3.5" />
      <p>Some error message here...</p>
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const model = defineModel<string>()

const props = withDefaults(
  defineProps<{
    // Label Contents
    name: string
    label?: string
    value?: string
    error?: string
    placeholder?: string

    // Input Property
    type?: 'text' | 'password' | 'email' | 'number'
    disable?: boolean
    required?: boolean
    autocomplete?: 'on' | 'off' | 'username' | 'email' | (string & {})

    variant?: 'rounded' | 'circle'
    size?: 'small' | 'default' | 'large'
  }>(),
  { type: 'text', variant: 'rounded', size: 'default' },
)

const sizeClasses = { small: 'text-sm', default: 'text-base', large: 'text-lg' }
const variantClasses = { rounded: 'rounded', circle: 'rounded-full' }

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
