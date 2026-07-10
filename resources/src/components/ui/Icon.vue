<template>
  <component :is="icon" :class="[size]"></component>
</template>

<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  icon: string
  size?: 'small' | 'medium' | 'large' | 'default'
}

const props = withDefaults(defineProps<Props>(), {
  size: 'default',
})

const size = computed(() => {
  if (props.size === 'small') {
    return 'h-4 w-4'
  } else if (props.size === 'medium' || props.size === 'default') {
    return 'h-5 w-5'
  } else if (props.size === 'large') {
    return 'h-6 w-6'
  }
})
</script>

<style scoped></style>
