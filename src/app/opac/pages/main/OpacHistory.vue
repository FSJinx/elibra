<template>
  <aside class="hidden 2xl:block sticky top-20 w-100 shrink-0 bg-background border border-border divide-y divide-border rounded-xl overflow-hidden">
    <div class="flex items-center p-5">
      <div>
        <p class="text-[11px] font-semibold uppercase tracking-[0.2em] text-foreground-secondary">History</p>

        <h2 class="mt-1 text-xl font-semibold tracking-tight">Recent searches</h2>
      </div>
    </div>

    <!-- History Record -->
    <div class="flex flex-col gap-2">
      <!-- No History -->

      <div class="p-8 text-center text-muted-foreground" v-if="history.history.length <= 0">There's nothing here.</div>

      <!-- History List -->
      <ul v-else>
        <li class="flex items-center hover:bg-slate-50 px-5 py-4" v-for="item in formattedHistory" role="button">
          <div class="flex-1 flex items-center gap-4">
            <span class="size-9 flex items-center justify-center rounded-full bg-muted text-muted-foreground leading-0">
              <RotateCcwClock :size="19" />
            </span>

            <div class="">
              <span class=""> {{ item.title }} </span>
              <span class="block text-sm text-muted-foreground">{{ parse.timeAgo(item.datetime) }}</span>
            </div>
          </div>

          <Button variant="text" icon="x-lg" class="rounded-full! hover:bg-slate-200 transition-colors duration-150" @click="history.deleteHistory(item)"></Button>
        </li>
      </ul>
    </div>

    <div class="p-4 text-center" v-if="history.history.length >= 0">
      <button class="text-end ml-auto text-sm text-danger transition-colors cursor-pointer" @click="history.clearHistory()">Clear History</button>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { RotateCcwClock } from '@lucide/vue'

const history = opacSearchStore()
const parse = useParser()

const formattedHistory = computed(() => {
  const h = history.history.toReversed()
  const newHistory = []

  for (let index = 0; index < h.length; index++) {
    if (index >= 5) {
      continue
    } else {
      newHistory.push(h[index])
    }
  }

  return newHistory
})
</script>

<style scoped></style>
