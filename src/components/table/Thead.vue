<template>
  <thead class="sticky top-0 bg-slate-100 shadow-[0_1px_0_0_var(--color-border)] z-10">
    <slot />
  </thead>
</template>

<script setup lang="ts">
const slots = useSlots()

const th = computed(() => {
  const tr = slots.default?.()[0]

  return tr?.children?.length ?? 0
})

provide('th', th)
</script>

<style scoped></style>
