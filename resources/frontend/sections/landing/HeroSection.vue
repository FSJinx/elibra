<template>
  <section class="hero relative h-screen w-full overflow-hidden z-0 pt-18">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-no-repeat bg-center bg-cover opacity-10" :style="{ backgroundImage: `url(${images.gate})` }"></div>

    <!-- Content -->
    <div class="relative z-10 flex flex-col items-center h-full w-full mt-40 gap-8 sm:gap-10">
      <!-- <h1 class=" w-full text-center text-primary text-3xl md:text-5xl font-bold md:leading-normal">e-Libra: The ISU-1 Library Management and Resource Monitoring System</h1> -->
      <h1 class="w-full md:w-[65%] px-5 text-center text-primary text-3xl md:text-5xl font-bold leading-normal">A Centralized Web-Based Library Management System</h1>

      <form class="relative flex items-center bg-white rounded-full overflow-hidden">
        <input type="text" class="h-18 pl-7 pr-20 w-110 sm:w-200 outline-0 whitespace-nowrap text-ellipsis" placeholder="Try typing some keywords to search in our OPAC" />
        <button class="absolute right-0 mr-2 text-center rounded-full h-[80%] w-auto aspect-square bg-primary text-white"><Search class="m-auto" /></button>
      </form>

      <p class="text-center text-wrap w-[85%] md:w-180 leading-loose">e-Libra modernizes library services at Isabela State University by providing a cloud-based platform for efficient resource management, real-time access, and improved user experience for both librarians and patrons.</p>
    </div>
  </section>
</template>

<script setup>
import images from '@/assets/images'
</script>

<style scoped></style>
