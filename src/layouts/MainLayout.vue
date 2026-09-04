<template>
  <div class="flex flex-col min-h-dvh w-full">
    <FeatureBelt />
    <Header />
    <main class="flex-1 bg-linear-to-r from-secondary via-background to-secondary scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/25 transition-all duration-300">
      <slot></slot>
    </main>
    <Footer />
  </div>
</template>

<script setup lang="ts"></script>

<style scoped></style>
