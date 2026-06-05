<template>
  <button class="flex items-center justify-center gap-1 text-center cursor-pointer border transition-all duration-200 leading-snug" :class="[btnDesign]" :type="props.type">
    <SpinnerLoading size="sm" v-if="isLoading" />

    <component :is="icon" :class="iconSize" v-if="icon && !isLoading" />
    <span v-if="label" class="">{{ label }}</span>
  </button>
</template>

<script setup>
import { computed } from 'vue'
import SpinnerLoading from '../loading/SpinnerLoading.vue'

//#region Prop Definition
/**
 * @typedef { 'solid' | 'outline' | 'outline-solid' } Variants
 * @typedef { 'rounded' | 'circle' } Border
 * @typedef { 'small' | 'default' | 'large' } Sizes
 * @typedef { 'button' | 'submit' } Type
 * @typedef { 'primary' | 'default' | 'blue' | 'green' | 'red' | 'yellow' } Color
 */
//#endregion

const props = defineProps({
  // Content
  label: String,
  icon: String,

  // Design
  variant: {
    /** @type {import('vue').PropType<Variants>} */
    type: String,
    default: 'solid',
  },
  border: {
    /** @type {import('vue').PropType<Border>} */
    type: String,
    default: 'rounded',
  },
  size: {
    /**type {import('vue').PropType<Sizes>} */
    type: String,
    default: 'default',
  },
  type: {
    /** @type {import('vue').PropType<Type>} */
    type: String,
    default: 'button',
  },
  color: {
    /** @type {import('vue').PropType<Color>} */
    type: String,
    default: 'default',
  },

  //   State
  isLoading: { type: Boolean, default: false },
  disabled: { type: Boolean, default: false },
})

const btnDesign = computed(() => [getVariant.value, getSize.value, getBorder.value])

const getVariant = computed(() => {
  const colors = {
    solid: {
      default: 'text-white bg-gray-500 border-gray-500 hover:bg-gray-600',
      primary: 'text-white bg-primary border-primary hover:bg-green-700',
      green: 'text-white bg-green-500 border-green-500 hover:bg-green-600',
      blue: 'text-white bg-blue-500 border-blue-500 hover:bg-blue-600',
      red: 'text-white bg-red-500 border-red-500 hover:bg-red-600',
      yellow: 'text-white bg-yellow-500 border-yellow-500 hover:bg-yellow-600',
    },
    outline: {
      default: 'text-gray-700 bg-gray-50 border-gray-400 hover:bg-gray-100',
      primary: 'text-primary bg-white border-primary hover:bg-primary/5',
      green: 'text-green-500 bg-white border-green-500 hover:bg-green-50',
      blue: 'text-blue-500 bg-white border-blue-500 hover:bg-blue-50',
      red: 'text-red-500 bg-white border-red-500 hover:bg-red-50',
      yellow: 'text-yellow-600 bg-white border-yellow-500 hover:bg-yellow-50',
    },
    'outline-solid': {
      default: 'text-gray-700 bg-gray-50 border-gray-400 hover:bg-gray-500 hover:text-white',
      primary: 'text-primary bg-white border-primary  hover:bg-primary hover:text-white',
      green: 'text-green-500 bg-white border-green-500  hover:bg-green-500 hover:text-white',
      blue: 'text-blue-500 bg-white border-blue-500  hover:bg-blue-500 hover:text-white',
      red: 'text-red-500 bg-white border-red-500  hover:bg-red-500 hover:text-white',
      yellow: 'text-yellow-600 bg-white border-yellow-500  hover:bg-yellow-500 hover:text-white',
    },
  }

  return colors[props.variant ?? 'solid'][props.color ?? 'default']
})

// Size getter
const getSize = computed(() => {
  const sizeWithIconLabel = {
    small: 'px-3 py-1.5 text-sm',
    default: 'px-3 py-1.5 text-base',
    large: 'px-3 py-1.5 text-lg',
  }

  const sizeLabelOnly = {
    small: 'px-4 py-1.5 text-sm',
    default: 'px-5 py-1.5 text-base',
    large: 'px-5 py-1.5 text-lg',
  }

  const sizeIconOnly = {
    small: 'p-2 text-sm',
    default: 'p-2 text-base',
    large: 'p-2 text-lg',
  }

  if (props.icon && props.label) {
    return sizeWithIconLabel[props.size]
  } else if (props.icon && !props.label) {
    return sizeIconOnly[props.size]
  } else if (!props.icon && props.label) {
    return sizeLabelOnly[props.size]
  }
})

// Border getter
const getBorder = computed(() => {
  const border = {
    rounded: 'rounded',
    circle: 'rounded-full',
  }

  return border[props.border ?? 'rounded']
})

const iconSize = computed(() => {
  const sizes = {
    small: 'h-3 w-3',
    default: 'h-4 w-4',
    large: 'h-4.5 w-4.5',
  }
  return sizes[props.size]
})
</script>

<style scoped></style>
