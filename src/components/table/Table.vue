<template>
  <section class="relative flex-1 flex flex-col bg-background pb-5 min-h-100 border border-border rounded-xl overflow-hidden scrollbar-none">
    <!-- Table Header -->
    <div class="flex items-center justify-between border-b border-border/70 px-5 py-4" v-if="title || dataLength">
      <div>
        <h2 class="text font-semibold">{{ title }}</h2>

        <p class="mt-1 text-xs text-foreground-secondary" v-if="subtitle">{{ subtitle }}</p>
      </div>

      <span class="text-sm text-foreground-secondary" v-if="dataLength"> {{ dataLength }} items </span>
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
