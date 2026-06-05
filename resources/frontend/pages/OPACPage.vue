<template>
  <main class="relative flex flex-col min-h-screen w-full bg-gray-50 z-0">
    <div class="flex items-center justify-between w-full h-10 px-5 text-xs bg-primary-dark text-white">
      <div class="flex items-center">
        <span>Contact: +123456789</span>
      </div>
    </div>

    <!-- Header -->
    <div class="sticky top-0 flex items-center gap-3 w-full z-10 bg-white transition-all duration-300 ease-in-out" :class="isScrolled ? 'py-4 px-10 shadow-md' : 'py-10 px-40 shadow-sm'">
      <img :src="images.isu" alt="" class="h-10" />
      <div class="flex flex-col justify-between overflow-hidden whitespace-nowrap">
        <h1 class="text-xl font-extrabold text-primary leading-6.5">e-Libra</h1>
        <p class="text-sm hidden sm:inline">The ISU-One Library Management System of the <span class="font-bold text-primary">Isabela State University</span></p>
      </div>
      <div class="flex ml-auto">
        <div class="flex gap-2">
          <Button type="outline" color="primary" label="Login" />
          <Button type="solid" color="primary" label="Register" />
        </div>
      </div>
    </div>

    <section class="flex flex-1 w-full">
      <!-- Filter Section -->
      <div class="relative flex items-start min-w-95">
        <div class="flex flex-col rounded shadow h-auto w-full ml-auto bg-white p-4" :class="isScrolled ? 'top-24' : 'top-26'">
          <h1 class="font-bold text-primary mb-5">Refine Search</h1>

          <div class="flex flex-col gap-3">
            <div class="flex flex-col gap-2">
              <label for="material_type" class="text-sm text-primary">Material Type</label>
              <select name="material_type" id="material_type" class="border p-3 rounded border-primary">
                <option value="">Book</option>
              </select>
            </div>
            <div class="flex flex-col gap-2">
              <label for="material_type" class="text-sm text-primary">Material Type</label>
              <select name="material_type" id="material_type" class="border p-3 rounded border-primary">
                <option value="">Book</option>
              </select>
            </div>
            <div class="flex flex-col gap-2">
              <label for="material_type" class="text-sm text-primary">Material Type</label>
              <select name="material_type" id="material_type" class="border p-3 rounded border-primary">
                <option value="">Book</option>
              </select>
            </div>
            <div class="flex flex-col gap-2">
              <label for="material_type" class="text-sm text-primary">Material Type</label>
              <select name="material_type" id="material_type" class="border p-3 rounded border-primary">
                <option value="">Book</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Content Section -->
      <div class="relative w-full min-h-[150vh] bg-white">
        <!-- Search -->
        <div class="sticky top-20 flex items-center justify-end p-5 px-7 gap-3 bg-white shadow">
          <select name="material_type" id="material_type" class="border p-2 rounded border-primary">
            <option value="">Keyword</option>
          </select>
          <input type="text" placeholder="Search Here..." class="w-full p-2 px-5 border border-gray-300 rounded-md" />
          <Button type="solid" color="primary" label="Search" />
        </div>

        <div class="flex flex-col px-5 mt-5">
          <h1 class="text-3xl font-bold mb-5">Discover Your Taste</h1>

          <div class="flex items-start w-full bg-white border border-gray-300 rounded shadow-xs p-5 gap-3">
            <span>1.</span>
            <div class="border rounded w-25 h-40"></div>
            <div class="flex flex-col item-center w-full flex-1">
              <span class="w-max text-xs rounded-full bg-green-100 p-1 px-4 mb-2">Novel</span>
              <h3 class="font-semibold">Bad Buddy Novel</h3>
              <p class="text-sm">GMMTV, Inc</p>
            </div>
          </div>
        </div>
      </div>

      <div class="relative flex items-start min-w-90 p-5">
        <div class="flex flex-col rounded shadow h-auto w-90 mx-auto bg-white p-4">
          <h1 class="font-bold text-primary">Another Filter</h1>
        </div>
      </div>
    </section>
  </main>
</template>

<script setup>
import images from '@/assets/images'
import Button from '@/components/buttons/Button.vue'
import { onBeforeUnmount, onMounted, ref } from 'vue'

const isScrolled = ref(false)

const handleScroll = () => {
  if (window.scrollY > 20) {
    isScrolled.value = true
  } else {
    isScrolled.value = false
  }
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
})

onBeforeUnmount(() => {
  window.removeEventListener('scroll', handleScroll)
})
</script>
