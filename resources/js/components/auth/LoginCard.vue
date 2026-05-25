<template>
  <div class="bg-white rounded-lg h-auto w-125 p-10">
    <div class="flex flex-col items-center justify-center w-full mb-10 gap-1">
      <img :src="images.logo" alt="" class="h-20 w-auto" />
      <h1 class="text-2xl font-bold text-primary">Welcome Back!</h1>
      <p>Login now and continue where we left off.</p>
    </div>

    <form action="" class="w-full flex flex-col gap-5">
      <div class="flex flex-col gap-2">
        <label for="email" class="text-sm font-medium text-gray-700">Email/Username/Student ID Number</label>
        <input type="email" id="email" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Enter your email/username/student ID number" />
      </div>

      <div class="flex flex-col gap-2">
        <label for="password" class="text-sm font-medium text-gray-700">Password</label>
        <div class="w-full relative">
          <input :type="showPassword ? 'text' : 'password'" id="password" class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary" placeholder="Enter your password" />
          <Eye v-if="showPassword" @click="togglePasswordVisibility" class="absolute top-0 right-0 m-2.5 h-5 w-auto" />
          <EyeOff v-else @click="togglePasswordVisibility" class="absolute top-0 right-0 m-2.5 h-5 w-auto" />
        </div>
      </div>

      <router-link :to="{ name: '' }" class="text-sm text-right text-primary hover:underline">Forgot password?</router-link>
      <button type="submit" class="w-full bg-primary text-white py-2 rounded hover:bg-primary-dark transition duration-200">Login</button>
    </form>
    <p class="text-sm text-center mt-5">Don't have an account yet? <router-link :to="{ name: '' }" class="text-primary font-medium hover:underline">Register here</router-link></p>
  </div>
</template>

<script setup>
import images from '@/assets/images'
import { ref } from 'vue'

const showPassword = ref(false)
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}
</script>
