<template>
  <div class="logo flex items-center gap-1 select-none">
    <img :src="images.logo" alt="e-Libra" class="w-auto" :class="[logoHeight]" v-if="withLogo" />
    <span class="font-extrabold text-primary" :class="[fontSize]" v-if="withLabel">e-Libra</span>
  </div>
</template>

<script setup>
import images from '@/assets/images'

/**
 * @typedef {'h-10'} Height
 */

defineProps({
  withLogo: {
    type: Boolean,
    default: true,
    required: true,
  },
  withLabel: {
    type: Boolean,
    default: true,
    required: true,
  },
  fontSize: {
    type: String,
    default: 'text-2xl',
    required: true,
  },
  logoHeight: {
    /** @type {import('vue').PropType<Height>} */
    type: String,
    default: 'h-10',
  },
})
</script>

<style scoped></style>
