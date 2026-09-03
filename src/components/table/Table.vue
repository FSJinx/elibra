<template>
  <section class="relative flex-1 flex flex-col bg-background pb-5 min-h-100 border border-border rounded-xl overflow-hidden scrollbar-none">
    <!-- Table Header -->
    <div class="flex items-center justify-between border-b border-border px-5 py-4" v-if="title">
      <div class="">
        <h2 class="text-lg font-semibold">{{ title }}</h2>
        <p class="mt-0.5 text-sm text-foreground-secondary">{{ subtitle }}</p>
      </div>

      <p class="text-sm bg-muted/50 text-muted-foreground px-3 py-1 rounded-lg border border-border" v-if="dataLength">{{ dataLength }} records</p>
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
