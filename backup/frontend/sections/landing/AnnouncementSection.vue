<template>
  <section class="flex items-center relative bg-neutral-800 border-y-5 border-primary overflow-hidden">
    <!-- Prev Button -->
    <button class="absolute left-5 z-1 rounded-full bg-primary p-2 hover:scale-110 transition">
      <ChevronLeft class="w-8 h-8 m-auto text-white" />
    </button>

    <!-- Content Wrapper -->
    <div class="flex flex-col items-center max-w-7xl mx-auto p-10">
      <h1 class="text-4xl font-bold text-white text-center mb-12">Announcements</h1>

      <div class="flex flex-col lg:flex-row gap-10 items-center">
        <!-- Image -->
        <div class="border-2 border-primary rounded overflow-hidden shrink-0 max-w-md w-full aspect-square">
          <img :src="images.announcement" alt="Announcement" class="w-full h-full object-cover hover:scale-110 transition duration-300" />
        </div>

        <!-- Content -->
        <div class="flex flex-col flex-1">
          <h2 class="text-3xl font-bold text-white border-l-4 border-primary pl-5 mb-6">ISU Library Opens a Rest & Recharge Corner for Students</h2>

          <div class="space-y-5">
            <p class="text-gray-300 leading-relaxed">The Isabela State University Library has introduced a new feature designed to support student well-being...</p>

            <p class="text-gray-300 leading-relaxed">Located inside the main library, the area offers a quiet and calming atmosphere...</p>
          </div>

          <div class="mt-8 text-sm text-neutral-400">
            <p>Published on: June 20, 2024</p>
            <p>Posted By: ISU Echague</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import images from 'src/assets/images'
</script>
