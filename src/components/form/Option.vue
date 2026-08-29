<template>
  <button type="button" class="text-left px-4 py-2 hover:bg-primary hover:text-primary-foreground transition-all duration-100 flex items-center rounded disabled:bg-disabled disabled:text-muted disabled:cursor-not-allowed" :class="{ 'bg-primary text-primary-foreground': isSelected }" :disabled="disabled" @click="select.setSelected(value, labelText)">
    <span v-if="labelText" class="flex-1">
      {{ labelText }}
    </span>

    <slot v-else />
    <Icon class="ml-auto" :icon="isSelected ? 'check' : ''" />
  </button>
</template>

<script setup lang="ts">
interface Props {
  value: any
  disabled?: boolean
  selected?: boolean
}

const select = inject<any>('select')
const labelText = computed(() => content || '')
const slots = useSlots()
const content = computed(() => slots.default?.()[0].children ?? [])

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  selected: false,
})

const isSelected = computed(() => {
  if (!select) {
    return props.selected
  }

  return select.model?.value === props.value || select.selectedOption?.value === props.value || props.selected
})

onMounted(() => {
  if (select.selectedOption.value === props.value) {
    select.setSelected(props.value, labelText.value)
  }
})
</script>
