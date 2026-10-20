// src/plugins/axios.ts
import axios, { type AxiosRequestConfig } from 'axios'

import router from '@/router'
import { authStore } from '@/stores/auth.store'

// export const backendRoute = `${(import.meta as any).env.VITE_APP_URL}/api`
export const backendRoute = `http://localhost:8000/api`

const pending = new Map<string, AbortController>()
const keyOf = (c: AxiosRequestConfig) => `${c.method}:${c.baseURL ?? ''}${c.url?.split('?')[0]}`
const clear = (c?: AxiosRequestConfig) => c && pending.get(keyOf(c))?.signal === c.signal && pending.delete(keyOf(c))

export const api = axios.create({
  baseURL: backendRoute,
})

let refreshed = false

// Request interceptor: inject token if exists
api.interceptors.request.use(
  async (config) => {
    const my = authStore()

    if (my.token) {
      config.headers.Authorization = `Bearer ${my.token}`
    }

    if (config.method === 'GET') {
      const key = keyOf(config)
      pending.get(key)?.abort()

      const ctrl = new AbortController()
      pending.set(key, ctrl)
      config.signal = ctrl.signal
    }

    return config
  },
  (error) => Promise.reject(error),
)

api.interceptors.response.use(
  (res) => res,
  async (error) => {
    const my = authStore()
    const pop = usePopup()
    const status = error.response?.status
    const response = error.response?.data

    if (status === 401) {
      if (refreshed) {
        my.clearUser()
        pop.error('Session expired, please re-login to continue.')
        router.push({ name: 'home' })
        return Promise.reject(error)
      }

      refreshed = true
      try {
        const refreshResponse = await api.post('auth/refresh')
        const newToken = refreshResponse.data?.data?.token
        const newUserData = refreshResponse.data?.data?.user

        if (!newToken) {
          throw error
        }

        my.setToken(newToken)
        my.setUser(newUserData)

        error.config.headers.Authorization = `Bearer ${newToken}`

        return api(error.config)
      } catch (refreshError) {
        my.clearUser()
        pop.error('Session expired, please re-login to continue.')
        router.push({ name: 'home' })
        return Promise.reject(refreshError)
      } finally {
        refreshed = false
      }
    }

    // if (status === 403) {
    //   pop.error(response?.message)
    // }

    // if (status === 404) {
    //   // pop.unload()
    //   pop.error(response?.message)
    // }

    if (status === 422) {
      // pop.unload()

      const errors = response.errors
      const firstErrorMessage = Object.values(errors as object)[0][0]
      console.log(firstErrorMessage)

      pop.error(firstErrorMessage ?? response?.message)
    }

    if (status === 500) {
      pop.error('Server error, please try again later.')
    }

    return Promise.reject(error)
  },
)

export default api
