<template>
  <section class="relative flex-1 flex flex-col min-h-150 border-y border-border overflow-hidden scrollbar-none">
    <div class="relative flex-1 shrink-0 overflow-y-auto scrollbar-none bg-background">
      <table class="relative w-full text-left table-auto text-[12.5px] border-collapse">
        <slot />
      </table>
    </div>
  </section>
</template>
