// src/plugins/axios.js
import { useUserStore } from '@/stores/auth'

import axios from 'axios'

export const backendRoute = `${import.meta.env.VITE_APP_URL}/api`

const api = axios.create({
  baseURL: backendRoute,
})

// Request interceptor: inject token if exists
api.interceptors.request.use(
  async (config) => {
    const my = useUserStore()
    const token = my.token

    if (token) {
      config.headers.Authorization = `Bearer ${token}`
    }
    return config
  },
  (error) => Promise.reject(error),
)

api.interceptors.response.use(
  res => res,
  async (error) => {
    const originalRequest = error.config

    if (error.response?.status === 401 && !originalRequest._retry) {
      originalRequest._retry = true
      
      
    }

  }
)

export default api

// // handle token expiration / refresh
// axios.interceptors.response.use(
//   res => res,
//   async error => {
//     const originalRequest = error.config

//     // only retry once
//     if (error.response?.status === 401 && !originalRequest._retry) {
//       originalRequest._retry = true
//       try {
//         const { data } = await axios.post('/refresh', null, {
//           headers: { Authorization: `Bearer ${localStorage.getItem('refresh_token')}` }
//         })

//         localStorage.setItem('access_token', data.access_token)
//         originalRequest.headers.Authorization = `Bearer ${data.access_token}`
//         return axios(originalRequest) // retry original request
//       } catch (refreshError) {
//         // refresh failed → user really unauthorized
//         logoutUser()
//         return Promise.reject(refreshError)
//       }
//     }

//     return Promise.reject(error)
//   }
// )

// ✅ This applies to all API calls automatically.
// ✅ Prevents infinite loops.
// ✅ Only retries once per request.
// ✅ Handles refresh token expiration gracefully.
//#endregion
