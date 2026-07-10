<template>
  <button class="flex items-center justify-center rounded-sm p-2 px-3 gap-2 border transition-all duration-200 hover:border-slate-300 cursor-pointer" :class="active ? 'border-slate-300 shadow' : 'border-transparent hover:bg-slate-50'" @click="$emit('click')">
    <Icon icon="bell"></Icon>
    <span class="text-base leading-none"> Notifications </span>
  </button>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Icon from '../../../components/ui/Icon.vue'

defineEmits<{
  (e: 'click'): void
}>()

interface Props {
  show?: boolean
  hasNotification: boolean
}

const props = withDefaults(
  defineProps<{
    active?: boolean
  }>(),
  {
    active: false,
  },
)
</script>

<style scoped></style>
