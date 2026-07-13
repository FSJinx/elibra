<template>
  <span class="shrink-0 text-xs p-2 py-1 leading-normal" :class="[badgeClass]">
    <slot />
  </span>
</template>
<script setup lang="ts">
import { computed, ref } from 'vue'
import { getRadius, type Radius } from '../../composables/useRadius'

type Border = true | false

interface Props {
  radius: Radius
  border?: Border
}

const props = withDefaults(defineProps<Props>(), {
  radius: 'cube',
  border: false,
})

const badgeClass = computed(() => {
  return [getRadius(props.radius)]
})
</script>
