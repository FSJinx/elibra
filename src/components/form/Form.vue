<template>
  <form @submit.prevent="$emit('submit')" class="flex flex-col">
    <div class="flex mb-3" v-if="$slots.header">
      <slot name="header" />
    </div>

    <slot />

    <div class="grid gap-5" :class="[`grid-cols-${cols}`]">
      <slot name="body" />
    </div>

    <div class="mt-2" v-if="$slots.footer">
      <slot name="footer" />
    </div>
  </form>
</template>

<script setup lang="ts">
interface Props {
  cols?: string
}

withDefaults(defineProps<Props>(), {
  cols: '1',
})

defineEmits(['submit'])
</script>

<style scoped></style>
