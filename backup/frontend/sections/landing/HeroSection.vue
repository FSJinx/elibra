<template>
  <section class="hero relative flex justify-center h-screen w-full overflow-hidden z-0 pt-10">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-no-repeat bg-center bg-cover opacity-10" :style="{ backgroundImage: `url(${images.gate})` }"></div>

    <div class="flex items-center h-full w-[90%] md:w-[70vw] z-10">
      <!-- Content -->
      <div class="flex flex-col items-center w-full gap-8 sm:gap-10">
        <h1 class="max-w-250 px-5 text-center text-primary text-4xl md:text-5xl font-bold leading-normal">e-Libra: A Centralized Web-Based Library Management System</h1>

        <form class="relative flex items-center w-full md:max-w-200 bg-white rounded-full overflow-hidden">
          <input type="text" class="h-18 pl-7 pr-20 w-full outline-0 whitespace-nowrap text-ellipsis" placeholder="Try typing some keywords to search in our OPAC" />
          <button class="absolute right-0 mr-2 text-center rounded-full h-[80%] w-auto aspect-square bg-primary text-white"><Search class="m-auto" /></button>
        </form>

        <p class="text-center text-wrap w-full md:max-w-200 leading-loose">e-Libra modernizes library services at Isabela State University by providing a cloud-based platform for efficient resource management, real-time access, and improved user experience for both librarians and patrons.</p>
      </div>
    </div>
  </section>
</template>

<script setup>
import images from 'src/assets/images'
</script>

<style scoped></style>
