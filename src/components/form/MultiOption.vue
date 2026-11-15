<template>
  <div role="option" :aria-selected="selected" @click="ctx.toggle(value)" class="flex items-center gap-2 px-3 py-2 rounded-md text-foreground cursor-pointer hover:bg-primary/10 transition-colors" :class="{ 'bg-primary/10 font-medium': selected }">
    <span class="grid items-start size-5 shrink-0 rounded border transition-colors" :class="selected ? 'bg-primary border-primary text-white' : 'border-border'">
      <Icon v-if="selected" icon="check" class="mb-3" />
    </span>
    
    <span class="line-clamp-1">{{ label }}</span>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{ value: any; label: string }>()

const ctx = inject<any>('multiselect')!
const selected = computed(() => ctx.isSelected(props.value))

// Register on setup so the chips can show the label even before the menu is opened
watch(
  () => props.label,
  (label) => ctx.register(props.value, label),
  { immediate: true },
)
</script>
