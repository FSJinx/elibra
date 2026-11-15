<template>
  <button type="button" class="group text-left px-4 py-2 hover:bg-primary hover:text-primary-foreground focus:bg-primary focus:text-primary-foreground transition-all duration-100 flex items-center rounded disabled:bg-disabled disabled:text-muted-foreground disabled:cursor-not-allowed" :class="{ 'bg-primary text-primary-foreground': isSelected }" :disabled="disabled" @click="select.setSelected(value, labelText)">
    <div v-if="label">
      <p>{{ label }}</p>
      <p class="text-sm text-muted-foreground group-hover:text-primary-foreground/75" :class="{ 'text-primary-foreground/75': isSelected }" v-if="description">{{ description }}</p>
    </div>

    <span v-else-if="labelText" class="flex-1">
      <p>{{ labelText }}</p>
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

  label?: string
  description?: string
}

const select = inject<any>('select')
const labelText = computed(() => content || '')
const slots = useSlots()
const content = computed(() => props.label ?? slots.default?.()[0].children ?? [])

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

const isValueMatch = (left: any, right: any) => {
  if (left == null || right == null) {
    return left === right
  }

  return String(left) === String(right)
}

watch(
  [() => select?.model?.value, labelText],
  ([modelValue, label]) => {
    if (isValueMatch(modelValue, props.value) && label) {
      select.setSelected(props.value, label)
    }
  },
  { immediate: true },
)
</script>
