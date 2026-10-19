<template>
  <section class="relative flex-1 flex flex-col bg-background border border-border rounded-xl overflow-hidden scrollbar-none">
    <!-- Table Header -->
    <div class="grid grid-cols-2 items-center gap-2 border-b border-border/70 px-5 py-4" v-if="title || dataLength">
      <div>
        <h2 class="text-xl font-semibold">{{ title }}</h2>

        <p class="mt-1 text-sm text-foreground-secondary" v-if="subtitle">{{ subtitle }}</p>
      </div>

      <slot name="header" v-if="$slots.header" />
      <span class="text-sm text-foreground-secondary ml-auto" v-else-if="dataLength"> {{ Number(dataLength) }} items </span>
    </div>

    <!-- Table Body -->
    <div class="relative flex-1 shrink-0 overflow-y-auto bg-background">
      <table class="relative w-full text-left table-auto text-[12.5px] border-collapse" :class="[layout]">
        <slot />
      </table>
    </div>
  </section>
</template>

<script setup lang="ts">
interface Props {
  dataLength?: string | number | undefined
  title?: string
  subtitle?: string
  layout?: 'auto' | 'fixed'
}

const props = withDefaults(defineProps<Props>(), {
  dataLength: 0,
  layout: 'auto',
})

const layout = computed(() => {
  const layouts: Record<NonNullable<Props['layout']>, string> = {
    auto: 'table-auto',
    fixed: 'table-fixed',
  }

  return layouts[props.layout]
})
</script>
