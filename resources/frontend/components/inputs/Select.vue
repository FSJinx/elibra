<template>
  <div class="relative z-51" ref="dropdown">
    <button @click="toggleDropdown" type="button" class="flex justify-between items-center w-full border bg-white rounded-sm pl-4 pr-2 py-1 text-primary border-primary hover:bg-primary/5 cursor-pointer">
      <span class="text-start w-full text-sm text-ellipsis whitespace-nowrap overflow-hidden">
        {{ selected?.label ?? 'Please select something...' }}
      </span>

      <ChevronDown class="ml-auto min-w-4 min-h-4" />
    </button>

    <Transition name="options">
      <div v-if="isOpen" class="absolute z-50 flex flex-col mt-2 w-max max-w-100 max-h-70 rounded-sm border border-primary bg-white shadow-lg overflow-hidden select-none">
        <div class="flex flex-col w-full overflow-auto p-3">
          <button v-for="(option, index) in select.options" :key="option.value" @click="setSelected(option)" class="flex items-center justify-between cursor-pointer rounded px-5 py-3 text-left hover:bg-gray-50 hover:text-secondary" :class="['', { hidden: index === 0 }, { 'bg-primary text-white': selected?.value === option.value }]" :title="option.label">
            <span class="w-full text-ellipsis whitespace-nowrap overflow-hidden">{{ option.label }}</span>
            <span class="ml-3 w-4 flex justify-end" v-if="selected.value === option.value"><Check class="h-4 w-4" /></span>
          </button>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, watchEffect, onMounted, onBeforeUnmount } from 'vue'

// Props
const select = defineProps({
  options: {
    type: Array,
    default: () => [],
  },
})

// State
const isOpen = ref(false)
const selected = ref(null)
const dropdown = ref(null)

// Default selected
watchEffect(() => {
  if (select.options.length && !selected.value) {
    selected.value = select.options[0]
  }
})

// Methods
function toggleDropdown() {
  isOpen.value = !isOpen.value
}

function setSelected(option) {
  if (option.value === '' || selected.value === option.value) return

  selected.value = option
  isOpen.value = false
}

function handleOutsideClick(event) {
  if (dropdown.value && !dropdown.value.contains(event.target)) {
    isOpen.value = false
  }
}

// Lifecycle
onMounted(() => {
  window.addEventListener('click', handleOutsideClick)
})

onBeforeUnmount(() => {
  window.removeEventListener('click', handleOutsideClick)
})
</script>

<style>
.options-enter-active,
.options-leave-active {
  transition:
    transform 0.2s ease,
    opacity 0.2s ease;
}

.options-enter-from,
.options-leave-to {
  transform: translateY(-5px);
  opacity: 0;
}
</style>
