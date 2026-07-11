<template>
  <div class="relative overflow-y-auto">
    <div class="flex-1 relative min-h-0">
      <table class="w-full text-center">
        <thead class="sticky top-0 bg-slate-50 z-10 shadow-sm">
          <tr>
            <slot name="head" />
          </tr>
        </thead>

        <tbody class="overflow-y-auto">
          <slot name="body" />
        </tbody>
      </table>
    </div>
  </div>
</template>
