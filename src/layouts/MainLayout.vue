<template>
  <FeatureBelt />
  <Header />
  <div class="flex flex-col min-h-dvh w-vw">
    <main class="flex-1 bg-linear-to-r from-secondary via-background to-secondary scrollbar-thumb-transparent hover:scrollbar-thumb-foreground/25 transition-all duration-300">
      <slot></slot>
    </main>
  </div>
  <Footer />
</template>

<script setup lang="ts"></script>

<style scoped></style>
