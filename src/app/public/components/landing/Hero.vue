<template>
  <div class="relative w-full max-w-[100rem] p-10 my-10 space-y-10 mx-auto">
    <!-- ISU Header -->
    <div class="flex flex-col items-center font-baskervville text-center sm:text-3xl">
      <div class="flex items-center justify-center gap-2 mb-5 sm:mb-7">
        <img :src="isu" alt="" class="size-15 sm:size-25 z-2 rounded-full" />
        <div class="absolute size-15 sm:size-25 z-1 rounded-full bg-primary/25 blur-md"></div>
      </div>

      <p class="font-medium text-[75%]">The Online Public Access Catalog (OPAC) of the</p>
      <div class="text-[160%] uppercase font-semibold text-green-700 tracking-tight"><span class="text-[125%]">I</span>sabela <span class="text-[125%]">S</span>tate <span class="text-[125%]">U</span>niversity</div>
    </div>

    <!-- Search Box Here -->

    <Form class="flex h-10 sm:h-16 w-full max-w-4xl mx-auto rounded-xl border border-border focus-within:border-primary focus-within:ring-5 focus-within:ring-primary/20 overflow-hidden transition-all duration-300">
      <Button class="rounded-none! bg-slate-50 h-full sm:text-lg hover:text-primary" variant="text" left-icon="sliders2">Advanced</Button>
      <input type="text" class="flex-1 px-5 sm:text-lg bg-background leading-0 border-x border-border outline-none" placeholder="Search for title, author, or call number..." />
      <Button class="rounded-none! bg-slate-50 h-full sm:text-lg hover:text-primary" variant="text" left-icon="search">Search</Button>
    </Form>
  </div>
</template>

<script setup lang="ts">
import isu from '@/assets/images/isu.png'
</script>

<style scoped></style>
