<template>
  <!-- .Profile -->
  <div class="h-21 border-t border-slate-200 mt-auto z-2 bg-white p-3">
    <span class="flex h-full items-center text-lg gap-3 px-3 rounded cursor-pointer select-none hover:bg-gray-200" ref="miniBtn">
      <img :src="images.user" alt="" class="h-8 w-auto rounded-full" />
      <span class="flex flex-1 flex-col justify-center gap-0.5">
        <p class="text-sm text-ellipsis line-clamp-1 text-wrap">Juan Dela Cruz</p>
        <p class="text-xs text-neutral-400">Administrator</p>
      </span>
      <ChevronDown />
    </span>
  </div>
</template>

<script setup lang="ts">
import images from '@/assets/images'
import { ChevronDown } from '@lucide/vue'
</script>

<style scoped></style>
