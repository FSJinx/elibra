<template>
  <router-link :to="{ name: 'Home' }" class="logo flex items-center gap-2 select-none">
    <img :src="images.logo" alt="e-Libra" class="w-auto" :class="[logoHeight]" v-if="withLogo" />
    <span class="font-extrabold text-primary" :class="[fontSize]" v-if="withLabel">e-Libra</span>
  </router-link>
</template>

<script setup>
import images from 'src/assets/images'

/**
 * @typedef {'h-10'} Height
 */

defineProps({
  withLogo: {
    type: Boolean,
    default: true,
    required: false,
  },
  withLabel: {
    type: Boolean,
    default: true,
  },
  fontSize: {
    type: String,
    default: 'text-2xl',
  },
  logoHeight: {
    /** @type {import('vue').PropType<Height>} */
    type: String,
    default: 'h-10',
  },
})
</script>

<style scoped></style>
