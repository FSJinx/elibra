<template>
  <div class="bg-white border border-neutral-100 rounded-lg p-5 shadow-sm hover:shadow-md transition flex flex-col gap-4 overflow-hidden">
    <!-- TOP ROW -->
    <div class="flex items-center justify-between">
      <span class="p-3 bg-primary rounded-sm" v-if="props.icon">
        <component :is="props.icon" class="w-6 h-6 text-white" />
      </span>

      <span v-if="props.value !== props.oldValue" class="text-xs font-semibold px-2 py-1 rounded-full ml-auto" :class="props.value > props.oldValue ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600'">
        {{ computeValue(props.oldValue, props.value) }}
      </span>
    </div>

    <div class="flex flex-col items-end mt-5">
      <!-- VALUE -->
      <div class="text-2xl font-bold text-neutral-800 tracking-tight">
        {{ Number(props.value).toLocaleString() }}
      </div>

      <!-- LABEL -->
      <div class="text-sm text-neutral-400">
        {{ props.label }}
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  oldValue: {
    type: Number,
    default: 0,
    required: true,
  },
  value: {
    type: Number,
    default: 0,
    required: true,
  },
  icon: {
    type: String,
    required: false,
  },
})

const computeValue = (old, val) => {
  if (old > val) {
    return `- ${Number((val / old) * 100).toFixed(1)}%`
  } else if (old < val) {
    return `+ ${Number((old / val) * 100).toFixed(1)}%`
  }
}
</script>
