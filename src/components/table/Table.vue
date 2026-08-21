<template>
  <section class="relative flex-1 flex flex-col min-h-150 border-y border-border overflow-hidden scrollbar-none">
    <div class="relative flex-1 shrink-0 overflow-y-auto scrollbar-none bg-background">
      <table class="relative w-full text-left table-auto text-sm border-collapse">
        <slot />
      </table>
    </div>
  </section>
</template>

<!-- <script setup lang="ts">
interface Props {
  name: string
  description?: string
}

const query = defineModel<string>()

const props = defineProps<Props>()
const emit = defineEmits(['refresh', 'search', 'create'])
const timer = ref()

watchDebounced(query, (newVal) => {
  if (!newVal) {
    emit('search', query.value)
  } else {
    if (timer.value) {
      clearTimeout(timer.value)
    }

    timer.value = setTimeout(() => {
      emit('search', query.value)
    }, 500)
  }
})
</script> -->
