<template>
  <Button icon-left="bell" class="border transition-all duration-200 hover:border-slate-300" :class="active ? 'border-slate-300 shadow' : 'border-transparent hover:bg-slate-50'" @click="$emit('click')">
    <span class="text-base leading-none"> Notifications </span>
  </Button>
</template>

<script setup lang="ts">

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
