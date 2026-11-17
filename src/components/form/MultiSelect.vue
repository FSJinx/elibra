<template>
  <div ref="dropdownRef" class="relative isolate inline-flex flex-col gap-2 min-w-40 w-full">
    <div
      @click="toggle"
      class="flex items-center min-h-11 text-left px-4 py-1.5 text-foreground w-full bg-background rounded-md border outline-none focus-visible:ring-4 ring-primary/20 focus-visible:border-success cursor-pointer disabled:cursor-not-allowed transition-all duration-200"
      :class="[open ? 'ring-4 border-success' : 'border-border', { 'pointer-events-none! cursor-not-allowed!': disabled }]"
      :aria-expanded="open"
      aria-haspopup="listbox"
      :data-title="enableTooltip ? `${parse.toCapital(title as string)}: ${selected.map((s) => s.label).join(', ')}` : ''"
    >
      <div class="flex flex-wrap items-center gap-1.5 mr-5">
        <span v-if="!selected.length" class="line-clamp-1">{{ placeholder }}</span>

        <span v-for="s in selected" :key="s.value" class="inline-flex items-center gap-1 max-w-full rounded bg-primary/10 pl-2 pr-1 py-0.5 text-sm">
          <span class="line-clamp-1">{{ s.label }}</span>
          <Icon icon="x" class="cursor-pointer rounded hover:bg-primary/20" @click.stop="toggleValue(s.value)" />
        </span>
      </div>
      <Icon icon="chevron-down" class="ml-auto shrink-0 transition-all duration-300 pointer-events-none" :class="{ '-rotate-180': open }" />
    </div>

    <p v-if="error && error.length > 0" class="text-xs font-medium text-danger">
      {{ error }}
    </p>

    <!-- Helper Text -->
    <div v-if="helper" class="flex items-start gap-1.5 mt-1 text-sm text-info">
      <Icon icon="info-circle" />
      <span>{{ helper }}</span>
    </div>

    <!-- Hidden input, only for native `required` validation -->
    <Input :id="`${id || control?.id}-value`" class="absolute opacity-0 -z-1 pointer-events-none h-full w-full" :required="required" :model-value="model.join(',')" />

    <!-- Teleported dropdown: using hidden instead of v-if keeps option slots mounted (so labels stay registered) -->
    <Teleport to="body">
      <div ref="dropdownMenuRef" role="listbox" aria-multiselectable="true" :class="[open ? 'grid animate-dropdown-in' : 'hidden']" class="options gap-0.5 fixed bg-background min-w-75 rounded-md shadow-lg border border-border p-2 z-9999 max-h-60 overflow-y-auto scrollbar-thin" :style="dropdownStyle">
        <p v-if="props.title" class="text-xs font-semibold uppercase text-foreground-secondary p-1 mb-1">{{ props.title }}</p>
        <slot />
      </div>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
interface Props {
  id?: string
  title?: string
  required?: boolean
  disabled?: boolean
  enableTooltip?: boolean
  error?: string | null
  helper?: string
  placeholder?: string
}

const dropdownRef = ref<HTMLElement | null>(null)
const dropdownMenuRef = ref<HTMLElement | null>(null)
const open = ref<boolean>(false)
const model = defineModel<any[]>({ default: () => [] })
const parse = useParser()
const control = inject<any>('control', null)

const emit = defineEmits(['onChange'])
const props = withDefaults(defineProps<Props>(), {
  enableTooltip: false,
  error: '',
})

// value -> label, filled by each <MultiOption> on setup
const labels = reactive(new Map<any, string>())

const selected = computed(() => model.value.map((value) => ({ value, label: labels.get(value) ?? String(value) })))

const isSelected = (value: any) => model.value.includes(value)

const toggleValue = (value: any) => {
  model.value = isSelected(value) ? model.value.filter((v) => v !== value) : [...model.value, value]
  emit('onChange', model.value)
}

const register = (value: any, label: string) => labels.set(value, label)

const dropdownStyle = ref({
  top: '0px',
  left: '0px',
  width: '0px',
  minWidth: '300px',
  maxHeight: '240px',
})

const updatePosition = () => {
  if (dropdownRef.value && dropdownMenuRef.value) {
    const rect = dropdownRef.value.getBoundingClientRect()
    const spacing = 8
    const gap = 6
    const minWidth = 300
    const maxHeight = 240

    const spaceBelow = window.innerHeight - rect.bottom - gap - spacing
    const spaceAbove = rect.top - gap - spacing

    const openUpward = spaceBelow < maxHeight && spaceAbove > spaceBelow

    const availableHeight = Math.max(0, Math.min(maxHeight, openUpward ? spaceAbove : spaceBelow))

    // Actual dropdown height, limited by available viewport space
    const menuHeight = Math.min(dropdownMenuRef.value.scrollHeight, availableHeight)

    let left = rect.left

    if (rect.left + minWidth > window.innerWidth - spacing) {
      left = rect.right - minWidth
    }

    dropdownStyle.value = {
      top: `${openUpward ? rect.top - gap - menuHeight - 5 : rect.bottom + gap}px`,
      left: `${left}px`,
      width: `${rect.width}px`,
      minWidth: `${minWidth}px`,
      maxHeight: `${availableHeight}px`,
    }
  }
}

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

const handleScrollOrResize = () => {
  if (open.value) {
    updatePosition()
  }
}

// Chips can wrap to a new line, which changes the trigger's height -> reposition the menu
watch(
  () => model.value.length,
  () => open.value && nextTick(updatePosition),
)

// The menu is teleported outside dropdownRef, so check both refs. Otherwise clicking an option counts as "outside" and closes it.
const handlePointerDown = (e: MouseEvent) => {
  const target = e.target as Node
  if (!dropdownRef.value?.contains(target) && !dropdownMenuRef.value?.contains(target)) close()
}

onMounted(() => {
  nextTick(updatePosition)

  window.addEventListener('scroll', handleScrollOrResize, true)
  window.addEventListener('resize', handleScrollOrResize)
  document.addEventListener('mousedown', handlePointerDown)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScrollOrResize, true)
  window.removeEventListener('resize', handleScrollOrResize)
  document.removeEventListener('mousedown', handlePointerDown)
})

provide('multiselect', {
  model,
  isSelected,
  toggle: toggleValue,
  register,
})
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
