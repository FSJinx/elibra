<template>
  <!-- Title Page -->
  <div class="grid grid-cols-1 sm:grid-cols-2 p-6 pb-0">
    <div class="space-y-1">
      <p class="text-xs font-bold uppercase tracking-widest text-primary">{{ route.meta.title }}</p>
      <h1 class="mt-1 text-lg sm:text-2xl font-bold tracking-tight">{{ title }}</h1>
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
