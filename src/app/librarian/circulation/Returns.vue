<template>
  <div class="size-full flex flex-col">
    <div class="flex flex-col bg-background border-b border-border">
      <SectionHeader title="Check-out / Loans" description="Manage patrons' loans and returns." icon="laptop" />
    </div>

    <div class="flex-1 flex flex-col">
      <router-view />
    </div>
  </div>
</template>
