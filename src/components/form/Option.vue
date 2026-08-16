<template>
  <button type="button" class="text-left px-3 py-2 hover:bg-primary hover:text-primary-foreground transition-all duration-100 flex items-center gap-2 rounded disabled:bg-disabled disabled:text-muted disabled:cursor-not-allowed" :class="{ 'bg-primary text-primary-foreground': isSelected }" :disabled="disabled" @click="select.setSelected(value, labelText)">
    <span v-if="labelText">
      {{ labelText }}
    </span>

    <slot v-else />

    <Icon v-if="isSelected" class="ml-auto" icon="check" />
  </button>
</template>

<script setup lang="ts">
interface Props {
  value: any
  label?: string
  disabled?: boolean
  selected?: boolean
}

const select = inject<any>('select')

const props = withDefaults(defineProps<Props>(), {
  label: '',
  disabled: false,
  selected: false,
})

const labelText = computed(() => props.label || '')
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
