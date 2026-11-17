<template>
  <div class="flex flex-col gap-2 h-full">
    <h5 class="text-xs tracking-wide text-muted-foreground" v-if="label">{{ label }}</h5>
    <p class="font-semibold mt-auto">
      <slot>{{ value ?? '-' }}</slot>
    </p>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ label?: string; value?: any }>()
</script>

<style scoped></style>
