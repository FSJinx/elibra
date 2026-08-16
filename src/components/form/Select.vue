<template>
  <div ref="dropdownRef" class="relative flex items-center">
    <div type="button" @click="toggle" class="relative flex items-center h-10 text-left px-3 bg-secondary w-full rounded-md border border-border cursor-pointer disabled:cursor-not-allowed" :disabled="disabled" :aria-expanded="open" aria-haspopup="listbox">
      {{ selectedOption.label }}
      <Icon icon="chevron-down" class="absolute right-5 transition-all duration-150 pointer-events-none" :class="{ '-rotate-180': open }" />
      <Input :id="`${id}-value`" class="absolute opacity-0 -z-1 pointer-events-none h-full w-full" :required="required" v-model="model" />
    </div>

    <Transition name="dropdown">
      <div role="listbox" :class="[open ? 'grid' : 'hidden']" class="options grid gap-0.5 absolute w-full bg-background rounded-md shadow border border-border mt-2 p-2 top-full z-50 max-h-100 overflow-y-auto scrollbar-thin">
        <slot />
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
interface SelectedOption {
  value: any
  label: string
}

interface Props {
  id: string
  required?: boolean
  disabled?: boolean
}

const dropdownRef = ref<HTMLElement | null>(null)
const open = ref<boolean>(false)
const model = defineModel<any>({ default: '' })

const props = withDefaults(defineProps<Props>(), {})

const selectedOption = reactive<SelectedOption>({
  value: model.value,
  label: '',
})

watch(
  selectedOption,
  (s) => {
    model.value = selectedOption.value
    console.log(s)
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
.dropdown-enter-active,
.dropdown-leave-active {
  transition: all 100ms ease-in-out;
  opacity: 1;
}

.dropdown-enter-from,
.dropdown-leave-to {
  opacity: 0;
  transform: translateY(-5px);
}
</style>
