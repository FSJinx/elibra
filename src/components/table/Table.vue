<template>
  <section class="relative flex-1 flex flex-col bg-background min-h-100 border border-border rounded-xl overflow-hidden scrollbar-none">
    <!-- Table Header -->
    <div class="grid grid-cols-2 items-center gap-2 border-b border-border/70 px-5 py-4" v-if="title || dataLength">
      <div>
        <h2 class="text-xl font-semibold">{{ title }}</h2>

        <p class="mt-1 text-sm text-foreground-secondary" v-if="subtitle">{{ subtitle }}</p>
      </div>

      <slot name="header" v-if="$slots.header" />
      <span class="text-sm text-foreground-secondary ml-auto" v-else-if="dataLength"> {{ dataLength }} items </span>
    </div>

    <!-- Table Body -->
    <div class="relative flex-1 shrink-0 overflow-y-auto scrollbar-none bg-background">
      <table class="relative w-full text-left table-auto text-[12.5px] border-collapse">
        <slot />
      </table>
    </div>
  </section>
</template>

<script setup lang="ts">
interface Props {
  dataLength?: number | undefined
  title?: string
  subtitle?: string
}

const props = withDefaults(defineProps<Props>(), {
  dataLength: 0,
})
</script>
