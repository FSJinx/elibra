<template>
  <router-link :to="{ name: link }" class="flex items-center justify-center max-w-max text-center gap-1.5 rounded px-5 py-2 text-sm cursor-pointer transition-all duration-200 border" :class="[variant(color, type)]">
    <component :is="icon" v-if="icon" class="h-3.5 w-3.5" />
    <span v-if="label">{{ label }}</span>
  </router-link>
</template>

<script setup>
import variant from '@/constants/ButtonColor'

/**
 * @typedef {'blue' | 'purple' | 'green' | 'red' | 'yellow' | 'default'} Colors
 * @typedef { 'solid' | 'soft' | 'outline' } Type
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

  link: {
    type: String,
    default: '',
  },
})
</script>
