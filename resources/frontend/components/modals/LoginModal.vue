<template>
  <ModalLayout ref="login" :hasInputs="!!hasInputs">
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
        <BaseInput label="Username" name="username" placeholder="Username / Email / ID Number" type="username" required autocomplete v-model="username" />
        <BaseInput label="Password" name="password" placeholder="Enter your password" type="password" required v-model="password" />
        <router-link to="#" class="text-sm hover:underline underline-offset-2 text-primary text-end mr-1 ml-auto">Forgot Password?</router-link>
        <Button type="solid" color="primary" label="Login" :isLoading="isLoading" />
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
import BaseInput from '../BaseInput.vue'
import api from '@/plugins/axios.js'
import { useUserStore } from '@/stores/auth.js'
import elpop from '@/plugins/elpop.js'

const login = ref(null)
const username = ref('')
const password = ref('')
const isLoading = ref(false)
const hasInputs = ref(username.value || password.value)

const my = useUserStore()

async function submit() {
  isLoading.value = true

  await api
    .post('/auth/login', {
      username: username.value,
      password: password.value,
    })
    .then(async (res) => {
      if (res.data.status === 'success') {
        my.setToken(res.data.token)

        my.home()
        resetModal()
      }
    })
    .catch((err) => {
      elpop.error(err.response.data.message)
    })

  isLoading.value = false
}

function resetModal() {
  username.value = ''
  password.value = ''
  hasInputs.value = false

  login.value?.close()
}

defineExpose({
  open: () => login.value?.open(),
})
</script>
