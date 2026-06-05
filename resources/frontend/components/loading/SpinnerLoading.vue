<template>
  <div :class="spinnerClasses" role="status" aria-label="loading">
    <span class="sr-only">Loading...</span>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'md', // sm, md, lg, xl
  },
  variant: {
    type: String,
    default: 'current', // primary, white, current
  },
})

// Dynamic classes para sa sukat at kulay
const spinnerClasses = computed(() => {
  const base = 'animate-spin rounded-full border-2 border-solid border-t-transparent'

  // Sukat ng Spinner (Width at Height ay laging pantay)
  const sizes = {
    sm: 'w-4 h-4 border-2', // Bagay sa loob ng maliit na button
    md: 'w-6 h-6 border-2', // Standard size
    lg: 'w-10 h-10 border-3', // Para sa mga card o maliliit na section
    xl: 'w-16 h-16 border-4', // Pang-buong page loading state
  }

  // Kulay ng Spinner (border-current means gagayahin niya ang kulay ng text ng parent)
  const variants = {
    current: 'border-current',
    primary: 'border-blue-600',
    white: 'border-white',
    gray: 'border-gray-400',
  }

  return `${base} ${sizes[props.size]} ${variants[props.variant]}`
})
</script>
