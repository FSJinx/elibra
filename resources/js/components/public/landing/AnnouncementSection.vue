<template>
  <section class="flex flex-col bg-neutral-800 h-auto w-full p-5">
    <h1 class="text-3xl font-bold text-white mt-5 mx-auto">Announcements</h1>

    <!-- Announcement Cards -->
    <div class="flex justify-between">
      <button class="announcement-page-btn">
        <ChevronLeft class="text-white h-10 w-10 mx-auto" />
      </button>
      <!-- Announcement Content -->
      <div class="flex p-10 gap-10">
        <div class="flex items-center justify-center rounded border-2 border-primary h-100 w-100 cursor-pointer overflow-hidden">
          <img :src="images.announcement" alt="Announcement Image" class="object-cover h-full w-auto hover:scale-110 transition-all duration-300" />
        </div>
        <div class="flex flex-col flex-1">
          <h1 class="text-white font-bold mb-5 text-2xl border-primary border-l-5 py-3 px-5">ISU Library Opens a Rest & Recharge Corner for Students</h1>
          <!-- Main Content -->
          <div class="flex flex-col gap-5">
            <p class="text-gray-300">The Isabela State University Library has introduced a new feature designed to support student well-being—a cozy Rest & Recharge Corner equipped with soft carpet flooring and bean bags. This inviting space aims to give students a comfortable place to pause, relax, and regain their energy in between hours of reading, research, and academic work.</p>
            <p class="text-gray-300">Located inside the main library, the area offers a quiet and calming atmosphere where students can settle down, stretch out, or simply take a moment to breathe. Many students have already begun using the space, finding it helpful</p>
          </div>
          <div class="flex flex-col gap-1 text-neutral-400 text-sm mt-auto">
            <p class="">Published on: June 20, 2024</p>
            <p>Posted By: ISU Echague</p>
          </div>
        </div>
      </div>
      <button class="announcement-page-btn">
        <ChevronRight class="text-white h-10 w-10 mx-auto" />
      </button>
    </div>
  </section>
</template>

<script setup>
import images from '@/assets/images'
</script>
