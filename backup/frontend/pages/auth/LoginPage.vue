<template>
  <div class="relative flex items-center justify-center min-h-screen w-full bg-primary-dark p-4">
    <img :src="images.gate" alt="" class="absolute top-0 object-cover z-1 h-full w-full opacity-10" />

    <div class="flex flex-col items-center justify-center w-full sm:w-130 min-h-150 p-8 sm:p-10 gap-8 bg-white rounded-xl shadow-2xl z-10">
      <!-- Header Section -->
      <div class="flex flex-col items-center gap-2 text-center">
        <!-- added object-contain para hindi mag-distort ang logo -->
        <img :src="images.isu" alt="ISU Logo" class="h-24 w-auto aspect-square object-contain" />
        <h1 class="text-primary-dark font-extrabold text-3xl tracking-tight">Welcome Back!</h1>
        <p class="text-gray-500 text-sm sm:text-base">Login now and let's get back to where we left off.</p>
      </div>

      <form @submit.prevent="handleLogin" class="flex flex-col gap-4 w-full">
        <BaseInput label="Username" name="username" autocomplete="on" variant="rounded" size="large" validate />
        <BaseInput label="Password" name="password" type="password" autocomplete="on" variant="rounded" size="large" validate required />

        <button type="button" class="text-sm ml-auto mr-0.5 text-primary underline hover:text-primary-dark transition-colors cursor-pointer self-end">Forgot Password?</button>

        <Button label="Login" color="primary" size="large" type="submit" class="w-full mt-2" />
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import images from 'src/assets/images'
import BaseInput from '@/components/BaseInput.vue'
import Button from '@/components/buttons/Button.vue'

const handleLogin = () => {
  // Dito mo ilalagay ang login logic mo (API call, validation, etc.)
  console.log('Login form submitted')
}
</script>

<style scoped></style>
