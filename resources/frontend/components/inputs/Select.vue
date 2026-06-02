<template>
  <div class="relative inline-block min-w-35">
    <div class="flex items-center justify-between gap-2 p-2 px-4 text-primary border border-primary rounded cursor-pointer hover:bg-secondary/5 z-100" @click="toggleSelection">
      <p class="truncate">
        {{ selected?.label || label }}
      </p>
      <ChevronDown class="transition-transform" :class="{ 'rotate-180': open }" />
    </div>
    <div class="fixed inset-0 w-full h-full z-10">
      <div v-if="openSelection" class="absolute z-50 w-full mt-1 overflow-hidden bg-white border border-primary rounded shadow-lg">
        <div class="px-4 py-2 bg-neutral-100 text-gray-400" @click="select(null)">
          {{ label }}
        </div>
        <div v-for="item in options" :key="item.value" class="text-sm px-4 py-2 cursor-pointer hover:bg-secondary/10" @click="select(item)">
          {{ item.label }}
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue'
import { ChevronDown } from 'lucide-vue-next'

const props = defineProps({
  modelValue: {
    default: null,
  },

  label: {
    type: String,
    default: 'Select',
  },

  options: {
    type: Array,
    required: true,
  },
})

const emit = defineEmits(['update:modelValue'])

const openSelection = ref(false)

let selected = ref(null)

function toggleSelection() {
  openSelection = !openSelection
}

function select(item) {
  if (item) {
    selected = item
    emit('update:modelValue', item)
  }
  toggleSelection()
}
</script>
