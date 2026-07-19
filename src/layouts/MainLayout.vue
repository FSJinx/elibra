<template>
  <div class="relative flex flex-col min-h-dvh">
    <FeatureBelt />
    <Header />

    <main class="flex-1 flex flex-col min-h-[90dvh]">
      <slot />
    </main>

    <Footer />
  </div>
</template>

<script setup lang="ts"></script>

<style scoped></style>
