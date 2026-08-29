<template>
  <div ref="dropdownRef" class="relative inline-flex items-center min-w-40 w-full">
    <div type="button" @click="toggle" class="flex items-center h-11 text-left px-4 text-foreground w-full rounded-md border border-border cursor-pointer disabled:cursor-not-allowed transition-all duration-200" :class="[open ? 'bg-tertiary' : 'bg-background']" :disabled="disabled" :aria-expanded="open" aria-haspopup="listbox" :data-title="enableTooltip ? `${parse.toCapital(title as string)}: ${selectedOption.label}` : ''">
      <span class="line-clamp-1 mr-5">{{ selectedOption.label }}</span>
      <Icon icon="chevron-down" class="ml-auto transition-all duration-300 pointer-events-none" :class="{ '-rotate-180': open }" />
    </div>

    <Input :id="`${id}-value`" class="absolute opacity-0 -z-1 pointer-events-none h-full w-full" :required="required" v-model="model" />

    <!-- Teleported dropdown: using hidden / v-show instead of v-if keeps child slots mounted -->
    <Teleport to="body">
      <div role="listbox" :class="[open ? 'grid animate-dropdown-in' : 'hidden']" class="options gap-0.5 fixed bg-background rounded-md shadow-lg border border-border p-2 z-9999 max-h-60 overflow-y-auto scrollbar-thin" :style="dropdownStyle">
        <p v-if="props.title" class="text-xs font-semibold uppercase text-foreground-secondary p-1 mb-1">{{ props.title }}</p>
        <slot />
      </div>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, watch, provide, nextTick, onMounted, onUnmounted } from 'vue'

interface SelectedOption {
  value: any
  label: string
}

interface Props {
  id: string
  title?: string
  required?: boolean
  disabled?: boolean
  enableTooltip?: boolean
}

const dropdownRef = ref<HTMLElement | null>(null)
const open = ref<boolean>(false)
const model = defineModel<any>({ default: '' })
const parse = useParser()

const props = withDefaults(defineProps<Props>(), {
  enableTooltip: false,
})

const selectedOption = reactive<SelectedOption>({
  value: model.value,
  label: '',
})

const dropdownStyle = ref({
  top: '0px',
  left: '0px',
  width: '0px',
})

const updatePosition = () => {
  if (dropdownRef.value) {
    const rect = dropdownRef.value.getBoundingClientRect()
    dropdownStyle.value = {
      top: `${rect.bottom + 6}px`,
      left: `${rect.left}px`,
      width: `${rect.width}px`,
    }
  }
}

watch(
  selectedOption,
  () => {
    model.value = selectedOption.value
  },
  { deep: true, immediate: true },
)

const toggle = () => {
  if (!props.disabled) {
    open.value = !open.value
    if (open.value) {
      nextTick(updatePosition)
    }
  }
}

const close = () => {
  open.value = false
}

const setSelected = (selectedValue: any, selectedLabel: string) => {
  selectedOption.value = selectedValue
  selectedOption.label = selectedLabel
  close()
}

const handleScrollOrResize = () => {
  if (open.value) {
    updatePosition()
  }
}

onMounted(() => {
  // Compute initial width and position once mounted
  nextTick(updatePosition)

  window.addEventListener('scroll', handleScrollOrResize, true)
  window.addEventListener('resize', handleScrollOrResize)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScrollOrResize, true)
  window.removeEventListener('resize', handleScrollOrResize)
})

provide('select', {
  model,
  selectedOption,
  setSelected,
})

useClickOutside(dropdownRef, close)
</script>

<style scoped>
@keyframes dropdownIn {
  0% {
    opacity: 0;
    transform: translateY(-8px) scale(0.97);
  }

  100% {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.animate-dropdown-in {
  animation: dropdownIn 200ms cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
