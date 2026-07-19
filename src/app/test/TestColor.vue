<template>
  <div class="">
    <h1 class="section-title">Primary</h1>
    <div class="section">
      <div class="grid grid-cols-2 gap-5 w-full">
        <h1 class="bg-primary text-primary-foreground hover:bg-primary-hover p-2 px-4 rounded-md">Primary</h1>
        <h1 class="bg-primary-soft text-primary-soft-foreground hover:bg-primary-soft-hover p-2 px-4 rounded-md">Primary Soft</h1>
      </div>
    </div>
  </div>

  <div class="">
    <h1 class="section-title">Secondary</h1>
    <div class="section">
      <div class="grid grid-cols-2 gap-5 w-full">
        <h1 class="bg-secondary text-secondary-foreground hover:bg-secondary-hover p-2 px-4 rounded-md">Secondary</h1>
        <h1 class="bg-secondary-soft text-secondary-soft-foreground hover:bg-secondary-soft-hover p-2 px-4 rounded-md">Secondary Soft</h1>
      </div>
    </div>
  </div>

  <div class="">
    <h1 class="section-title">Success</h1>
    <div class="section">
      <div class="grid grid-cols-2 gap-5 w-full">
        <h1 class="bg-success text-success-foreground hover:bg-success-hover p-2 px-4 rounded-md">Success</h1>
        <h1 class="bg-success-soft text-success-soft-foreground hover:bg-success-soft-hover p-2 px-4 rounded-md">Success Soft</h1>
      </div>
    </div>
  </div>

  <div class="">
    <h1 class="section-title">Warning</h1>
    <div class="section">
      <div class="grid grid-cols-2 gap-5 w-full">
        <h1 class="bg-warning text-warning-foreground hover:bg-warning-hover p-2 px-4 rounded-md">Warning</h1>
        <h1 class="bg-warning-soft text-warning-soft-foreground hover:bg-warning-soft-hover p-2 px-4 rounded-md">Warning Soft</h1>
      </div>
    </div>
  </div>

  <div class="">
    <h1 class="section-title">Danger</h1>
    <div class="section">
      <div class="grid grid-cols-2 gap-5 w-full">
        <h1 class="bg-danger text-danger-foreground hover:bg-danger-hover p-2 px-4 rounded-md">Danger</h1>
        <h1 class="bg-danger-soft text-danger-soft-foreground hover:bg-danger-soft-hover p-2 px-4 rounded-md">Danger Soft</h1>
      </div>
    </div>
  </div>

  <div class="">
    <h1 class="section-title">Muted</h1>
    <div class="section">
      <div class="grid grid-cols-2 gap-5 w-full">
        <h1 class="bg-muted text-muted-foreground p-2 px-4 rounded-md">Muted</h1>
        <h1 class="text-muted-foreground p-2 px-4 rounded-md">Muted</h1>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts"></script>

<style scoped>
.section-title {
  font-size: var(--text-xl);
  font-weight: var(--font-weight-semibold);
}

.section {
  display: flex;
  flex-wrap: wrap;
  gap: calc(var(--spacing) * 3);
  margin-block: 1.5rem;
}
</style>
