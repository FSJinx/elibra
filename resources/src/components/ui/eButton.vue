<template>
  <button :class="[size, getRadius(radius), getVariant(variant, color)]" class="text-shadow inline-flex shrink-0 items-center justify-center gap-1 border text-center leading-none px-3 cursor-pointer transition-all duration-200">
    <component :is="iconLeft" :class="[iconSize, 'mr-1']"></component>
    <slot />
    <component :is="iconRight" :class="[iconSize, 'ml-1']"></component>
  </button>
</template>

<!-- Imports and Initializations -->
<script setup lang="ts">
import { computed } from 'vue'
import { getRadius, Radius } from '../../composables/useRadius'
import { getVariant, type Colors, type Variants } from '../../composables/useVariants'

interface Props {
  radius?: Radius
  variant?: Variants
  size?: 'small' | 'medium' | 'large' | 'default'
  color?: Colors
  iconRight?: string
  iconLeft?: string
  loading?: true | false
}

const props = withDefaults(defineProps<Props>(), {
  size: 'default',
  loading: false,
  radius: 'cube',
  variant: 'default',
  color: 'text',
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
