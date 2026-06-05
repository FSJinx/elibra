<template>
  <div class="flex flex-col gap-3 w-full border-b border-gray-200 p-5">
    <!-- Title -->
    <h1 class="text-2xl font-bold">Loading</h1>

    <div class="grid grid-cols-2">
      <p class="my-auto">Bubble</p>
      <div class="flex w-full">
        <BubbleLoading />
      </div>
    </div>

    <div class="grid grid-cols-2">
      <p class="my-auto">Bars</p>
      <div class="flex w-full">
        <BarsLoading />
      </div>
    </div>

    <div class="grid grid-cols-2">
      <p class="my-auto">Spinner</p>
      <div class="flex w-full">
        <SpinnerLoading />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import BarsLoading from '@/components/loading/BarsLoading.vue'
import BubbleLoading from '@/components/loading/BubbleLoading.vue'
import SpinnerLoading from '@/components/loading/SpinnerLoading.vue'
</script>
