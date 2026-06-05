<template>
  <ModalLayout ref="login" :hasInputs="!!(username || password)">
    <section class="flex flex-col items-center p-10 gap-2">
      <div class="flex flex-col items-center mb-3 gap-3">
        <div class="flex items-center gap-1 h-15 mb-1">
          <img :src="images.isu" alt="" class="h-full w-auto" />
          <!-- <img :src="images.logo" alt="" class="h-full w-auto" /> -->
        </div>
        <h1 class="font-bold text-primary text-3xl">Login</h1>
        <p class="text-center text-sm w-[90%]">Welcome back! Login now and let's get back to where we left off.</p>
      </div>

      <form @submit.prevent="submit" class="flex flex-col w-full gap-4 mt-5">
        <div class="flex flex-col gap-2">
          <label for="username" class="text-sm text-primary">Username/ID-Number/Email</label>
          <input type="text" class="bg-white rounded-sm shadow border border-primary px-3 py-2" placeholder="juandelacruz" v-model="username" />
        </div>
        <div class="flex flex-col gap-2">
          <label for="username" class="text-sm text-primary">Password</label>
          <input type="password" class="bg-white rounded-sm shadow border border-primary px-3 py-2" placeholder="************" v-model="password" />
        </div>
        <a href="#" class="text-sm hover:underline underline-offset-2 text-primary text-end mr-1">Forgot Password?</a>
        <Button type="solid" color="primary" label="Login" />
        <span class="text-center text-sm">A patron but doesn't have an account yet? <a href="" class="text-primary hover:underline">Create now</a></span>
      </form>
    </section>
  </ModalLayout>
</template>

<script setup>
import { ref, watch } from 'vue'
import ModalLayout from '@/layouts/ModalLayout.vue'
import images from '@/assets/images'
import Button from '../buttons/Button.vue'

const login = ref(null)
const username = ref('')
const password = ref('')

function submit() {
  console.log('Username: ', username.value)
  console.log('Password: ', password.value)
}

defineExpose({
  open: () => login.value?.open(),
})
</script>
