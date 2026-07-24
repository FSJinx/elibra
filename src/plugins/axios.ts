// src/plugins/axios.ts
import axios from 'axios'

// import elpop from './elpop'
import router from '@/router'
import { authStore } from '@/stores/auth'

// export const backendRoute = `${(import.meta as any).env.VITE_APP_URL}/api`
export const backendRoute = `http://localhost:8001/api`
// export const backendRoute = `http://10.10.149.83:8000/api`

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
    return config
  },
  (error) => Promise.reject(error),
)

api.interceptors.response.use(
  (res) => res,
  async (error) => {
    const my = authStore()
    const status = error.response?.status
    const response = error.response?.data

    if (status === 401) {
      if (refreshed) {
        my.clearUser()
        // elpop.error('Session expired, please re-login to continue.')
        router.push({ name: 'Home' })
        return Promise.reject(error)
      }

      refreshed = true
      try {
        const refreshResponse = await api.post('auth/refresh')
        const newToken = refreshResponse.data?.data?.token

        if (!newToken) {
          throw error
        }

        await my.setToken(newToken)
        error.config.headers.Authorization = `Bearer ${newToken}`

        return api(error.config)
      } catch (refreshError) {
        my.clearUser()
        // elpop.error('Session expired, please re-login to continue.')
        router.push({ name: 'Home' })
        return Promise.reject(refreshError)
      } finally {
        refreshed = false
      }
    }

    if (status === 403) {
      //   elpop.error(response?.message || 'Access denied.')
    }

    if (status === 500) {
      //   elpop.error('Server error, please try again later.')
    }

    return Promise.reject(error)
  },
)

export default api
