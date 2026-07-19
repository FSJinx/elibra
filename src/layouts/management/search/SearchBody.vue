<template>
  <Transition name="pop-in">
    <div class="absolute top-11 translate-x-1/2 right-1/2 bg-white shadow-lg w-md rounded-xl border border-slate-200 flex flex-col overflow-hidden z-50" v-if="show">
      <div class="">
        <div class="text-sm p-5 w-sm line-clamp-1" v-if="searchableOptions.length > 0">Showing {{ searchableOptions.length }} result{{ searchableOptions.length > 1 ? 's' : '' }}</div>

        <div class="w-full overflow-y-auto transition-all duration-200" :class="show ? 'max-h-150 opacity-100' : 'max-h-0 opacity-0'">
          <div class="flex flex-col items-center gap-2 p-5 text-gray-500 text-center line-clamp-1" v-if="searchableOptions.length <= 0">
            <XCircle class="h-10 w-10" :stroke-width="1.5" />
            No result came up.
          </div>
          <template v-for="option in searchableOptions" v-else>
            <div class="flex items-center p-3 px-4 gap-3 hover:bg-slate-100 cursor-pointer">
              <div class="flex-1 space-y-1">
                <p>{{ option.name }}</p>
                <p class="text-sm text-slate-500">{{ option.description }}</p>
              </div>
              <span class="p-3 px-0">
                <ChevronRight class="h-4 w-4" />
              </span>
            </div>
          </template>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { XCircle } from '@lucide/vue'
import { computed, ref } from 'vue'
import { options } from './options'

const props = withDefaults(
  defineProps<{
    show?: boolean
    query: string
  }>(),
  {
    show: false,
  },
)

const searchableOptions = computed(() => {
  return options.value.filter((option) => {
    let name = option.name.trim().toLowerCase()
    let description = option.description.trim().toLowerCase()
    let q = props.query.trim().toLowerCase()

    return name.includes(q) || description.includes(q)
  })
})
</script>

<style scoped></style>
