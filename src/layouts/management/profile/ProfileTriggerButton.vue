<template>
  <Button class="border" :class="active ? 'border-slate-300 shadow-sm' : 'border-transparent hover:border-slate-300 hover:bg-slate-50'" @click="$emit('click')">
    <span class="h-5 w-5 mr-1 rounded-full border border-primary overflow-hidden">
      <img :src="images.user" alt="" class="w-15 h-15" />
    </span>
    <p class="text-base leading-none capitalize">{{ user?.first_name || 'Unknown' }}</p>
  </Button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

defineEmits(['click'])

const props = withDefaults(
  defineProps<{
    active?: boolean
  }>(),
  {
    active: false,
  },
)

const auth = authStore()
const user = computed(() => {
  return auth.user
})
</script>

<style scoped></style>
