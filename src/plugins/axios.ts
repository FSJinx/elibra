// src/plugins/axios.ts
import axios, { type AxiosRequestConfig } from 'axios'

import router from '@/router'
import { authStore } from '@/stores/auth.store'

// export const backendRoute = `${(import.meta as any).env.VITE_APP_URL}/api`
const apiRoute = 'http://localhost:8000'
export const backendRoute = `${apiRoute}/api`
export const storageRoute = `${apiRoute}/storage`

const pending = new Map<string, AbortController>()
const keyOf = (c: AxiosRequestConfig) => `${c.method}:${c.baseURL ?? ''}${c.url?.split('?')[0]}`
const clear = (c?: AxiosRequestConfig) => c && pending.get(keyOf(c))?.signal === c.signal && pending.delete(keyOf(c))

export const api = axios.create({
  baseURL: backendRoute,
})

let refreshPromise: Promise<string> | null = null

function handleExpiredSession() {
  const my = authStore()
  if (!my.token) return

  my.clearUser()
  usePopup().error('Session expired, please re-login to continue.')
  router.push({ name: 'home' })
}

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
      const request = error.config as (AxiosRequestConfig & { _retry?: boolean }) | undefined
      const refreshRequest = request?.url?.includes('auth/refresh')

      if (!request || refreshRequest || request._retry) {
        if (refreshRequest || request?._retry) handleExpiredSession()
        return Promise.reject(error)
      }

      request._retry = true
      try {
        if (!refreshPromise) {
          refreshPromise = api
            .post('auth/refresh')
            .then((refreshResponse) => {
              const newToken = refreshResponse.data?.data?.token
              const newUserData = refreshResponse.data?.data?.user

              if (!newToken) {
                throw new Error('Token refresh response did not contain a token.')
              }

              my.setToken(newToken)
              if (newUserData) my.setUser(newUserData)
              return newToken
            })
            .finally(() => {
              refreshPromise = null
            })
        }

        const newToken = await refreshPromise

        error.config.headers.Authorization = `Bearer ${newToken}`

        return api.request(request)
      } catch (refreshError) {
        handleExpiredSession()
        return Promise.reject(refreshError)
      }
    }

    if (status === 422) {
      // pop.unload()

      const firstErrorMessage = (Object.values(response?.errors ?? {})[0] as string[] | undefined)?.[0]
      console.log(firstErrorMessage)
      pop.error(firstErrorMessage ?? 'Invalid input, please check your inputs and try again.')
    }

    if (status === 500) {
      pop.error('Server error, please try again later.')
    }

    return Promise.reject(error)
  },
)

export default api
