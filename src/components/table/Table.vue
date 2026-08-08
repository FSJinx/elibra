<template>
  <section class="relative flex-1 flex flex-col shrink-0 rounded-xl border border-border overflow-hidden scrollbar-none">
    <div class="flex items-center justify-end gap-1 w-full p-4">
      <div class="mr-auto">
        <h1 class="tracking-tight font-semibold text-lg mr-auto">{{ name }}</h1>
        <p class="text-sm text-foreground-secondary line-clamp-1">{{ description ?? 'This is the default description for ' + name + '.' }}</p>
      </div>
      <form class="min-w-85" @submit.prevent="() => emit('search', query)">
        <Input id="searchQuery" enable-clear v-model="query" left-icon="search" placeholder="Search by name, code, address" />
      </form>

      <Button variant="info" icon="rotate-ccw" @click="emit('refresh')"></Button>
    </div>

    <div class="relative flex-1 shrink-0 border-t border-border overflow-y-auto scrollbar-none">
      <table class="relative w-full text-left table-auto text-sm border-collapse">
        <slot />
      </table>
    </div>
  </section>
</template>

<script setup lang="ts">
interface Props {
  name: string
  description?: string
}

const query = defineModel<string>()

const props = defineProps<Props>()
const emit = defineEmits(['refresh', 'search'])
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
</script>
