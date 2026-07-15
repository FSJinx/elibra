<template>
  <button type="button" class="inline-flex shrink-0 items-center justify-center h-10 w-10 border rounded-md cursor-pointer" :title="name" :class="[variants[variant][color], radi[radius]]">
    <component :is="icon" class="h-5 w-5"></component>
  </button>
</template>

<script setup lang="ts">
import { radi, type Radius } from '../../composables/useRadius'
import { variants, type Colors, type Variants } from '../../composables/useVariants'

interface Props {
  icon: string
  name: string
  variant?: Variants
  radius?: Radius
  color?: Colors
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  color: 'text',
  radius: 'cube',
})
</script>

<style scoped></style>
