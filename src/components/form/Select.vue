<template>
  <div ref="dropdownRef" class="relative inline-flex items-center min-w-40 w-full">
    <div type="button" @click="toggle" class="flex items-center h-11 text-left px-4 bg-background text-foreground w-full rounded-md border border-border cursor-pointer disabled:cursor-not-allowed" :disabled="disabled" :aria-expanded="open" aria-haspopup="listbox" :data-title="enableTooltip ? `${parse.toCapital(title as string)}: ${selectedOption.label}` : ''">
      <span class="line-clamp-1 mr-5">{{ selectedOption.label }}</span>
      <Icon icon="chevron-down" class="ml-auto transition-all duration-300 pointer-events-none" :class="{ '-rotate-180': open }" />
    </div>

    <Input :id="`${id}-value`" class="absolute opacity-0 -z-1 pointer-events-none h-full w-full" :required="required" v-model="model" />

    <!-- Pure CSS Animated Dropdown without <Transition> -->
    <div role="listbox" :class="[open ? 'grid animate-dropdown-in' : 'hidden']" class="options gap-0.5 absolute w-full min-w-75 bg-background rounded-md shadow border border-border mt-2 p-2 top-full left-0 right-0 z-50 max-h-100 overflow-y-auto scrollbar-thin">
      <p v-if="props.title" class="text-xs font-semibold uppercase text-foreground-secondary p-1 mb-1">{{ props.title }}</p>
      <slot />
    </div>
  </div>
</template>

<script setup lang="ts">
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

watch(
  selectedOption,
  (s) => {
    model.value = selectedOption.value
  },
  { deep: true, immediate: true },
)

const toggle = () => {
  if (!props.disabled) {
    open.value = !open.value
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
