<template>
  <footer class="w-full bg-mist-800 text-white z-10 overflow-hidden">
    <!-- Main Footer Content -->
    <div class="max-w-7xl mx-auto px-6 pt-12 pb-8 sm:px-10">
      <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-10 lg:gap-5">
        <!-- Branding Logos Section -->
        <div class="flex gap-5 items-center justify-start">
          <Logo width="90" class="text-primary hover:opacity-90 transition-opacity" />
          <div class="h-12 w-px bg-neutral-600"></div>
          <!-- Subtle separator line -->
          <IsuLogo width="90" class="hover:opacity-90 transition-opacity" />
        </div>

        <!-- Links and Info Links Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 w-full lg:w-auto">
          <!-- Address Info -->
          <div class="flex flex-col space-y-3 sm:max-w-65">
            <p class="font-semibold tracking-wide text-sm uppercase text-neutral-300">Isabela State University</p>
            <p class="text-sm text-neutral-400 leading-relaxed">2nd Floor Admin Building, San Fabian, Echague, Isabela, Philippines 3309</p>
          </div>

          <!-- University Links -->
          <div class="flex flex-col space-y-2.5">
            <p class="font-semibold tracking-wide text-sm uppercase text-neutral-300">University Links</p>
            <a href="#" class="text-sm text-neutral-400 hover:text-white hover:translate-x-1 transform transition duration-200 block">ISU Official Website</a>
            <a href="#" class="text-sm text-neutral-400 hover:text-white hover:translate-x-1 transform transition duration-200 block">Library Website</a>
          </div>

          <!-- Socials Links -->
          <div class="flex flex-col space-y-2.5">
            <p class="font-semibold tracking-wide text-sm uppercase text-neutral-300">Socials</p>
            <a href="#" class="text-sm text-neutral-400 hover:text-white transition duration-200 flex items-center gap-2 group">
              <i class="fi fi-brands-facebook text-neutral-400 group-hover:text-blue-500 transition-colors"></i>
              <span>Facebook</span>
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bottom Copyright Bar -->
    <div class="bg-primary text-white">
      <div class="max-w-7xl mx-auto px-6 h-12 flex flex-wrap items-center justify-center gap-x-4 gap-y-1 text-center">
        <div class="flex items-center gap-1.5">
          <IsuLogo width="18" />
          <span>Isabela State University</span>
        </div>
        <span class="hidden sm:inline text-white/40">|</span>
        <div class="flex items-center gap-1.5">
          <Logo width="18" />
          <span class="font-medium">e-Libra</span>
        </div>
        <span class="hidden sm:inline text-white/40">|</span>
        <p class="text-white/80">&copy; 2026 All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts"></script>

<style scoped></style>
