<template>
  <section class="relative h-[50vh] flex justify-center border-b-5 border-primary">
    <img :src="gate" alt="Isabela State University Gate" class="absolute top-0 left-0 w-full h-full object-cover opacity-10 z-0" />
    <div class="relative h-full z-10 text-center w-3/4 mx-auto flex flex-col justify-center items-center">
      <h1 class="text-5xl font-bold text-primary mb-4">A Centralized Web-Based Library Management System</h1>
      <p class="text-gray-700 text-lg mb-6">e-Libra modernizes library services at Isabela State University by providing a cloud-based platform for efficient resource management, real-time access, and improved user experience for both librarians and patrons.</p>
      <div class="flex justify-center gap-4">
        <a href="#" class="flex items-center gap-2 px-8 py-3 bg-primary text-white rounded hover:bg-primary-dark transition">
          <BookOpen class="w-4 h-4" />
          Browse Catalog
        </a>
        <a href="#" class="flex items-center gap-2 px-8 py-3 border border-primary text-primary rounded hover:bg-primary hover:text-white transition">
          <ArrowUpFromLine class="w-4 h-4" />
          Submit Thesis
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
import gate from '@/assets/images/isu_gate.jpg'
</script>
