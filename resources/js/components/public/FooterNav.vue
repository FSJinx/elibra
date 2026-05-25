<template>
  <footer class="bg-mist-800 text-white h-[25vh] w-full">
    <section class="h-full flex flex-col justify-between">
      <div class="h-full flex justify-between items-center">
        <div class="flex gap-5 justify-end">
          <img :src="images.isu" alt="ISU Logo" class="h-25 ml-10" />
          <img :src="images.logo" alt="ISU Logo" class="h-25 mr-10" />
        </div>
        <div class="flex justify-between gap-5">
          <div class="flex flex-col w-75">
            <p class="font-semibold mb-3">Isabela State University</p>
            <p class="text-sm text-neutral-400">2nd Floor Admin Building, San Fabian, Echague, Isabela, Philippines 3309</p>
          </div>
          <div class="flex flex-col w-50">
            <p class="font-semibold mb-3">University Links</p>
            <a href="#" class="text-neutral-400 underline hover:text-white transition duration-150">ISU Official Website</a>
            <a href="#" class="text-neutral-400 underline hover:text-white transition duration-150">Library Website</a>
            <a href="#" class="text-neutral-400 underline hover:text-white transition duration-150">Library Website</a>
          </div>
          <div class="flex flex-col w-75">
            <p class="font-semibold mb-3">Socials</p>
            <a href="#" class="text-neutral-400 underline hover:text-white transition duration-150 flex gap-2"><Facebook />Facebook</a>
          </div>
        </div>
      </div>

      <!-- Copyright Tag -->
      <div class="bg-primary text-white flex items-center justify-center p-2 gap-2 mt-auto">
        <img :src="images.isu" alt="ISU Logo" class="h-6.25" />
        <p>Isabela State University</p>
        |
        <img :src="images.logo_white" alt="e-Libra Logo" class="h-6" />
        <p class="">e-Libra</p>
        |
        <p class="">&copy; 2026 All rights reserved.</p>
      </div>
    </section>
  </footer>
</template>

<script setup>
import images from '@/assets/images'
</script>
