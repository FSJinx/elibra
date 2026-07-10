<template>
  <button :class="[size]" class="inline-flex items-center justify-center gap-1 text-center leading-none rounded-md px-4 border cursor-pointer">
    <component :is="iconLeft" :class="[iconSize, 'mr-1']"></component>
    <slot />
    <component :is="iconRight" :class="[iconSize, 'ml-1']"></component>
  </button>
</template>

<!-- Imports and Initializations -->
<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  size?: 'small' | 'medium' | 'large' | 'default'
  variant?: 'solid' | 'outline' | 'outline-solid'
  color?: 'primary' | 'secondary' | 'danger' | ''
  iconRight?: string
  iconLeft?: string
  loading?: true | false
}

const props = withDefaults(defineProps<Props>(), {
  size: 'default',
  loading: false
})
const size = computed(() => {
  if (props.size === 'small') {
    return 'text-sm h-8'
  } else if (props.size === 'medium' || props.size === 'default') {
    return 'text-base h-10'
  } else {
    return 'text-lg h-12'
  }
})

const iconSize = computed(() => {
  if (props.size === 'small') {
    return 'h-3 h-3'
  } else if (props.size === 'medium' || props.size === 'default') {
    return 'h-4 w-4'
  } else {
    return 'h-5 w-5'
  }
})
</script>

<style scoped></style>
