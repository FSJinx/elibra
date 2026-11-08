<template>
  <div class="space-y-2">
    <h5 class="uppercase font-semibold text-xs tracking-wider text-muted-foreground" v-if="label">{{ label }}</h5>
    <p class="font-semibold">
      <slot>{{ value ?? '-' }}</slot>
    </p>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ label?: string; value?: any }>()
</script>

<style scoped></style>
