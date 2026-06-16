<template>
  <button class="flex items-center justify-center gap-1 text-center border transition-all duration-200" :class="[btnDesign, { [`${setHover} cursor-pointer`]: !isLoading && !disabled }]" :type="props.type" :disabled="isLoading || disabled">
    <BarsLoading size="sm" v-if="isLoading" class="mr-2" />

    <template v-else>
      <component :is="icon" :class="iconSize" v-if="icon && !isLoading" />
      <span v-if="label" class="">{{ label }}</span>
    </template>
  </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import SpinnerLoading from '../loading/SpinnerLoading.vue'
import BubbleLoading from '../loading/BubbleLoading.vue'
import BarsLoading from '../loading/BarsLoading.vue'

const props = withDefaults(
  defineProps<{
    // Content
    label?: string
    icon?: string

    // Design
    variant?: 'solid' | 'outline' | 'outline-solid'
    border?: 'rounded' | 'circle'
    size?: 'small' | 'default' | 'large'
    type?: 'button' | 'submit'
    color?: 'primary' | 'default' | 'blue' | 'green' | 'red' | 'yellow'

    //   State
    isLoading?: boolean
    disabled?: boolean
  }>(),
  {
    variant: 'solid',
    rounded: 'rounded',
    size: 'default',
    type: 'button',
    color: 'default',
  },
)

const btnDesign = computed(() => [getVariant.value, getSize.value, getBorder.value])

const getVariant = computed(() => {
  const colors = {
    solid: {
      default: 'text-white bg-gray-500 border-gray-500',
      primary: 'text-white bg-primary border-primary',
      green: 'text-white bg-green-500 border-green-500',
      blue: 'text-white bg-blue-500 border-blue-500',
      red: 'text-white bg-red-500 border-red-500',
      yellow: 'text-white bg-yellow-500 border-yellow-500',
    },
    outline: {
      default: 'text-gray-700 bg-gray-50 border-gray-400',
      primary: 'text-primary bg-white border-primary',
      green: 'text-green-500 bg-white border-green-500',
      blue: 'text-blue-500 bg-white border-blue-500',
      red: 'text-red-500 bg-white border-red-500',
      yellow: 'text-yellow-600 bg-white border-yellow-500',
    },
    'outline-solid': {
      default: 'text-gray-700 bg-gray-50 border-gray-400',
      primary: 'text-primary bg-white border-primary ',
      green: 'text-green-500 bg-white border-green-500 ',
      blue: 'text-blue-500 bg-white border-blue-500 ',
      red: 'text-red-500 bg-white border-red-500 ',
      yellow: 'text-yellow-600 bg-white border-yellow-500 ',
    },
  }

  return colors[props.variant ?? 'solid'][props.isLoading || props.disabled ? 'default' : props.color]
})

const setHover = computed(() => {
  const colors = {
    solid: {
      default: 'hover:bg-gray-600',
      primary: 'hover:bg-green-700',
      green: 'hover:bg-green-600',
      blue: 'hover:bg-blue-600',
      red: 'hover:bg-red-600',
      yellow: 'hover:bg-yellow-600',
    },
    outline: {
      default: 'hover:bg-gray-100',
      primary: 'hover:bg-primary/5',
      green: 'hover:bg-green-50',
      blue: 'hover:bg-blue-50',
      red: 'hover:bg-red-50',
      yellow: 'hover:bg-yellow-50',
    },
    'outline-solid': {
      default: 'hover:bg-gray-500 hover:text-white',
      primary: 'hover:bg-primary hover:text-white',
      green: 'hover:bg-green-500 hover:text-white',
      blue: 'hover:bg-blue-500 hover:text-white',
      red: 'hover:bg-red-500 hover:text-white',
      yellow: 'hover:bg-yellow-500 hover:text-white',
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
    small: 'p-2 px-3 text-sm',
    default: 'p-2 px-3 text-base',
    large: 'p-2 px-3 text-lg',
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
