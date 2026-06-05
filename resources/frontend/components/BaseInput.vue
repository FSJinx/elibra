<template>
  <div class="flex flex-col gap-1 w-full" :class="[getSize]">
    <span class="inline">
      <label :for="props.name" class="text-sm text-primary font-semibold w-100">{{ label }}</label>
      <span class="text-red-500 text-xs italic"> (required)</span>
    </span>
    <div class="relative flex items-center w-full">
      <input :type="props.type" class="px-4 py-1.5 w-full border no-spinner" :class="[inputClasses, props.required && model === '' ? 'border-red-500' : 'border-primary']" :name="props.name" :id="props.name" :placeholder="props.placeholder ?? props.label" :required="props.required" v-model="model" :autocomplete="props.autocomplete" />
      <CircleX v-if="model" class="h-5 w-5 z-10 absolute right-0 mr-2 text-gray-500 cursor-pointer" @click="model = ''" :title="`Clear ${label}`" />
    </div>
    <span class="flex items-center gap-1.5 text-sm text-red-500" v-if="props.error">
      <CircleAlert class="h-3.5 w-3.5" />
      <p>Some error message here...</p>
    </span>
  </div>
</template>

<script setup>
import { computed, watch } from 'vue'

/**
 * @typedef { 'text' | 'password' | 'email' | 'number' } Type
 * @typedef { 'rounded' | 'circle' } Variant
 */

const model = defineModel()

const props = defineProps({
  // Label Contents
  label: { type: String, required: true },
  name: { type: String, required: true },
  value: { type: String, default: '' },
  error: { type: String, default: undefined },

  // Input Property
  type: {
    /**@type {import('vue').PropType<Type>} */
    type: String,
    default: 'text',
  },
  placeholder: { type: String, default: undefined },
  disabled: { type: Boolean, default: false },
  required: { type: Boolean, default: false },
  autocomplete: { type: String, default: 'on' },

  //   Input Design
  variant: {
    /**@type {import('vue').PropType<Variant>} */
    type: String,
    default: 'rounded',
  },
  size: { type: String, default: 'default' },
})

const inputClasses = computed(() => [getVariant.value])

const getSize = computed(() => {
  const size = {
    small: 'text-sm',
    default: 'text-base',
    large: 'text-lg',
  }

  return size[props.size]
})

const getVariant = computed(() => {
  const variants = {
    rounded: 'rounded',
    circle: 'rounded-full',
  }

  return variants[props.variant]
})
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
