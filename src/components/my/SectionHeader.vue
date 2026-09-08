<template>
  <!-- Title Page -->
  <div class="grid grid-cols-1 sm:grid-cols-2 p-6 bg-background border-b border-border">
    <div class="space-y-1">
      <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-primary">{{ route.meta.title }}</p>
      <h1 class="mt-1 text-2xl sm:text-3xl font-semibold tracking-tight">{{ title }}</h1>
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

const route = useRoute()

const props = defineProps<Props>()
</script>

<style scoped></style>
