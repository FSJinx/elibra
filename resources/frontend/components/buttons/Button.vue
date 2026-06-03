<template>
  <button class="flex items-center justify-center text-center gap-1.5 rounded p-2 cursor-pointer transition-all duration-200 border" :class="[label || (label && icon) ? 'px-5 py-2 gap-2' : 'px-3 py-2', variant(color, type), btnWidth[width]]">
    <span class="min-h-3 min-w-3" v-if="icon" >
      <component :is="icon" class="h-4 w-4" />
    </span>
    <span v-if="label">{{ label }}</span>
  </button>
</template>

<script setup>
import variant from '@/constants/buttonColor'
import { ref } from 'vue'

/**
 * @typedef {'blue' | 'purple' | 'green' | 'red' | 'yellow' | 'default'} Colors
 * @typedef { 'solid' | 'soft' | 'outline' } Type
 * @typedef { 'full' | 'auto' | 'content'} ButtonWidth
 */

defineProps({
  icon: {
    type: String,
    required: false,
  },

  label: {
    type: String,
    required: false,
  },

  color: {
    /** @type {import('vue').PropType<Colors>} */
    type: String,
    required: false,
    default: 'default',
  },

  type: {
    /** @type {import('vue').PropType<Type>} */
    type: String,
    required: false,
    default: 'soft',
  },

  width: {
    /** @type {import('vue').PropType<Width>} */
    type: String,
    required: false,
    default: 'auto',
  },
})

const btnWidth = ref({
  full: 'w-full',
  auto: 'w-auto',
  content: 'max-w-max',
})
</script>
