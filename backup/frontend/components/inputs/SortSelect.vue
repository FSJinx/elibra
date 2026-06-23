<template>
  <div class="relative z-51" ref="dropdownRef">
    <button @click="toggleDropdown" type="button" class="flex justify-between items-center w-full border bg-white rounded-sm pl-4 pr-2 py-1 text-primary border-primary hover:bg-primary/5 cursor-pointer" :title="buttonLabel">
      <span class="text-start w-full text-sm text-ellipsis whitespace-nowrap overflow-hidden">
        {{ buttonLabel }}
      </span>
      <ChevronDown class="ml-auto min-w-4 min-h-4" />
    </button>

    <Transition name="options">
      <div v-if="isOpen" class="absolute z-50 flex flex-col mt-2 min-w-full w-110 max-w-150 rounded-sm border border-primary bg-white shadow-lg overflow-hidden select-none">
        <div class="flex flex-col">
          <div class="flex w-full items-start border-b border-gray-300">
            <!-- Order By -->
            <div class="flex flex-col items-start w-70">
              <p class="font-bold text-sm border-b border-gray-300 p-4 w-full">Order By</p>
              <div class="w-full overflow-y-auto p-2 h-full">
                <div class="flex items-center w-full gap-3 p-3 cursor-pointer hover:bg-secondary/5" @click="tempOrder = 'asc'">
                  <component :is="tempOrder === 'asc' ? 'SquareCheckBig' : 'Square'" class="h-4 w-4" />
                  <p>Ascending</p>
                </div>
                <div class="flex items-center w-full gap-3 p-3 cursor-pointer hover:bg-secondary/5" @click="tempOrder = 'desc'">
                  <component :is="tempOrder === 'desc' ? 'SquareCheckBig' : 'Square'" class="h-4 w-4" />
                  <p>Descending</p>
                </div>
              </div>
            </div>

            <!-- Sort By -->
            <div class="flex flex-col items-start w-full border-l border-gray-300">
              <p class="font-bold text-sm border-b border-gray-300 p-4 w-full">Sort By</p>
              <div class="w-full max-h-70 overflow-y-auto p-2">
                <div v-for="option in options" :key="option.value" class="flex items-center w-full gap-3 p-3 cursor-pointer hover:bg-secondary/5" @click="toggleTempSelection(option.value)">
                  <component :is="tempSort.includes(option.value) || (tempSort.length === 0 && option.value === '') ? 'SquareCheckBig' : 'Square'" class="h-4 w-4" />
                  <p>{{ option.label }}</p>
                </div>
              </div>
            </div>
          </div>

          <div class="w-full p-3 border-t border-gray-300 flex justify-end items-center">
            <Button type="solid" color="primary" label="Apply" @click="apply" />
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import Button from '../buttons/Button.vue'
import { ChevronDown, Square, SquareCheckBig } from 'lucide-vue-next' // Ensure icons are explicitly imported if not global

const props = defineProps({
  label: String,
  options: {
    type: Array,
    default: () => [],
  },
})

const emit = defineEmits(['value'])

// State
const isOpen = ref(false)
const dropdownRef = ref(null)

// Confirmed states (Emitted values)
const confirmedSort = ref([])
const confirmedOrder = ref('asc')

// Temporary states (What the user toggles while dropdown is open)
const tempSort = ref([])
const tempOrder = ref('asc')

// Computed label for better UX
const buttonLabel = computed(() => {
  if (confirmedSort.value.length === 0) return 'Sort By'

  const labels = props.options
    .filter((opt) => confirmedSort.value.includes(opt.value))
    .map((opt) => opt.label)
    .join(', ')

  return `Sorted By: ${labels} (${confirmedOrder.value === 'asc' ? 'Asc' : 'Desc'})`
})

// Methods
function toggleDropdown() {
  if (!isOpen.value) {
    // Open dropdown: sync draft states with confirmed states
    tempSort.value = [...confirmedSort.value]
    tempOrder.value = confirmedOrder.value
  }
  isOpen.value = !isOpen.value
}

function toggleTempSelection(value) {
  if (value === '') {
    tempSort.value = []
    return
  }

  const index = tempSort.value.indexOf(value)
  if (index > -1) {
    tempSort.value.splice(index, 1)
  } else {
    tempSort.value.push(value)
  }
}

function apply() {
  // Commit drafts to confirmed states
  confirmedSort.value = [...tempSort.value]
  confirmedOrder.value = tempOrder.value

  emit('value', {
    sort: confirmedSort.value,
    order: confirmedOrder.value,
  })
  isOpen.value = false
}

function handleOutsideClick(event) {
  if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
    // Closes and discards changes safely since we didn't touch confirmed states
    isOpen.value = false
  }
}

// Lifecycle
onMounted(() => window.addEventListener('click', handleOutsideClick))
onBeforeUnmount(() => window.removeEventListener('click', handleOutsideClick))
</script>
