<template>
  <span class="inline-flex shrink-0 text-xs p-2 py-1 leading-normal rounded-lg" :class="[badgeClass]">
    <slot />
  </span>
</template>
<script setup lang="ts">
import { computed, ref } from 'vue'

type Border = true | false

interface Props {
  border?: Border
}

const props = withDefaults(defineProps<Props>(), {
  border: false,
})

const badgeClass = computed(() => {
  return
})
</script>
