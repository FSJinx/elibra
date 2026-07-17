<template>
  <div class="relative min-h-screen w-full overflow-hidden bg-slate-950 px-4 py-8 sm:px-6 lg:px-8">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,0.22),transparent_34%),radial-gradient(circle_at_bottom_right,rgba(148,163,184,0.22),transparent_28%)]"></div>
    <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(15,23,42,0.96),rgba(15,23,42,0.88))]"></div>

    <div class="relative mx-auto flex min-h-[calc(100vh-4rem)] w-full max-w-6xl items-stretch overflow-hidden rounded-4xl border border-white/10 bg-white/5 shadow-2xl shadow-slate-950/30 backdrop-blur-xl">
      <section class="hidden w-full flex-col justify-between bg-slate-950 p-10 text-white lg:flex lg:w-[44%]">
        <div class="space-y-6">
          <img :src="images.isu" alt="ISU Logo" class="h-16 w-16 rounded-2xl bg-white/10 object-contain p-2" />
          <div class="space-y-3">
            <p class="text-sm font-semibold uppercase tracking-[0.35em] text-emerald-300">e-Libra</p>
            <h1 class="max-w-md text-4xl font-bold leading-tight text-white">Sign in to manage library services and subscriptions.</h1>
            <p class="max-w-md text-sm leading-6 text-slate-300">Use your username or email and password to access the administration portal securely through <span class="font-semibold text-white">/api/auth/login</span>.</p>
          </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm text-slate-300">
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
            <p class="font-semibold text-white">JWT auth</p>
            <p class="mt-1">Token-based login with automatic profile loading.</p>
          </div>
          <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
            <p class="font-semibold text-white">Role aware</p>
            <p class="mt-1">Redirects to the correct dashboard after login.</p>
          </div>
        </div>
      </section>

      <section class="flex w-full flex-col justify-center bg-white px-6 py-10 sm:px-10 lg:w-[56%] lg:px-14">
        <div class="mx-auto w-full max-w-md space-y-8">
          <div class="flex flex-col items-center gap-4 text-center lg:items-start lg:text-left">
            <img :src="images.isu" alt="ISU Logo" class="h-16 w-16 rounded-2xl border border-slate-200 object-contain p-2 shadow-sm" />
            <div class="space-y-2">
              <h2 class="text-3xl font-bold tracking-tight text-slate-900">Welcome back</h2>
              <p class="text-sm leading-6 text-slate-500">Log in to continue to your dashboard.</p>
            </div>
          </div>

          <form class="space-y-5" @submit.prevent="handleLogin">
            <div class="space-y-2">
              <label for="username" class="text-sm font-medium text-slate-700">Username or Email</label>
              <input id="username" v-model.trim="form.username" type="text" autocomplete="username" placeholder="Enter your username or email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" />
            </div>

            <div class="space-y-2">
              <label for="password" class="text-sm font-medium text-slate-700">Password</label>
              <div class="relative">
                <input id="password" v-model="form.password" :type="showPassword ? 'text' : 'password'" autocomplete="current-password" placeholder="Enter your password" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 pr-12 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-100" />
                <button type="button" class="absolute inset-y-0 right-0 flex items-center justify-center px-4 text-sm font-medium text-slate-500 transition hover:text-slate-700" @click="showPassword = !showPassword">
                  {{ showPassword ? 'Hide' : 'Show' }}
                </button>
              </div>
            </div>

            <div v-if="errorMessage" class="rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
              {{ errorMessage }}
            </div>

            <div class="flex items-center justify-between gap-4">
              <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                <input v-model="rememberMe" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500" />
                Remember me
              </label>

              <button type="button" class="text-sm font-medium text-emerald-700 transition hover:text-emerald-800">Forgot Password?</button>
            </div>

            <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 disabled:cursor-not-allowed disabled:bg-emerald-400" :disabled="isSubmitting">
              <span v-if="isSubmitting">Signing in...</span>
              <span v-else>Login</span>
            </button>
          </form>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import api from '@/plugins/axios'
import images from '@/assets/images'
import { authStore } from '@/stores/auth'

type LoginErrorResponse = {
  message?: string
  data?: unknown
}

const auth = authStore()

const form = reactive({
  username: '',
  password: '',
})

const isSubmitting = ref(false)
const showPassword = ref(false)
const rememberMe = ref(true)
const errorMessage = ref('')

const handleLogin = async () => {
  errorMessage.value = ''

  if (!form.username || !form.password) {
    errorMessage.value = 'Username and password are required.'
    return
  }

  isSubmitting.value = true

  try {
    const response = await api.post('auth/login', {
      username: form.username,
      password: form.password,
    })

    const token = response.data?.data?.token

    if (!token) {
      throw new Error('Login succeeded but no token was returned.')
    }

    await auth.setToken(token)
    await auth.getUser()

    if (!rememberMe.value) {
      localStorage.removeItem('token')
    }

  } catch (error) {
    const response = error as { response?: { data?: LoginErrorResponse } }
    errorMessage.value = response.response?.data?.message || 'Login failed. Please check your credentials and try again.'
  } finally {
    await auth.home()
    isSubmitting.value = false
  }
}
</script>

<style scoped></style>
