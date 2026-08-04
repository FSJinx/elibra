<template>
  <section class="relative flex-1 flex flex-col shrink-0 rounded-xl border border-border overflow-hidden scrollbar-none">
    <div class="flex items-center justify-end gap-2 w-full p-4">
      <div class="mr-auto">
        <h1 class="tracking-tight font-semibold text-lg mr-auto">{{ name }}</h1>
        <p class="text-sm text-foreground-secondary">{{ description ?? 'This is the default description for ' + name + '.' }}</p>
      </div>
      <Input id="searchQuery" v-model="search" left-icon="search" class="max-w-85" placeholder="Search by name, code, address" />

      <Button variant="info" icon="rotate-ccw" @click="emit('refresh')"></Button>
    </div>

    <div class="relative flex-1 shrink-0 border-t border-border overflow-y-auto scrollbar-none">
      <table class="relative w-full text-left table-fixed text-sm border-collapse">
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

const search = defineModel<string>()

const props = defineProps<Props>()
const emit = defineEmits(['refresh'])
</script>
