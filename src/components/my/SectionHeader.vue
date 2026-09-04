<template>
  <!-- Title Page -->
  <div class="grid grid-cols-1 sm:grid-cols-2 p-6">
    <div class="space-y-1">
      <h5 class="text-2xl font-semibold">{{ title }}</h5>
      <div class="flex items-center gap-2 text-muted-foreground text-sm">
        <Icon :icon="icon" v-if="icon" />
        <span>{{ description }}</span>
      </div>
    </div>

    <slot />
  </div>
</template>

<script setup lang="ts">
interface Props {
  title: string
  description: string
  icon?: string
}

const props = defineProps<Props>()
</script>

<style scoped></style>
