<template>
  <div class="rounded-md shadow-xs border border-gray-300 bg-white">
    <!-- Card Header -->
    <div class="border-b border-gray-300 p-4 px-6" v-if="$slots.header">
      <slot name="header" />
    </div>

    <!-- Card Body -->
    <div class="p-5">
      <slot />
    </div>

    <!-- Card Footer -->
    <div class="border-t border-gray-300 p-5" v-if="$slots.footer">
      <slot name="footer" />
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped></style>
