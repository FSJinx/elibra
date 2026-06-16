<template>
  <div class="rounded-md shadow-xs border border-gray-300 bg-white">
    <!-- Card Header -->
    <div class="flex items-center gap-2 border-b border-gray-300 p-4 px-6" v-if="$slots.header">
      <slot name="header" />
    </div>

    <!-- Card Body -->
    <div :class="[noPadding ? '' : 'p-5']">
      <slot />
    </div>

    <!-- Card Footer -->
    <div class="border-t border-gray-300 px-5 py-3" v-if="$slots.footer">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup>
defineProps({
  noPadding: {
    type: Boolean,
    default: false,
  },
})
</script>

<style scoped></style>
