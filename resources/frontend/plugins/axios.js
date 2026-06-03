// src/plugins/axios.js
import { useUserStore } from '@/stores/auth'

import axios from 'axios'

export const backendRoute = 'http://192.168.100.181:8000/api'
// export const backendRoute = 'http://127.0.0.1:8000/'
// export const backendRoute = "https://antiquewhite-cassowary-936393.hostingersite.com/";

const api = axios.create({
  baseURL: backendRoute,
})

// Request interceptor: inject token if exists
api.interceptors.request.use(
  async (config) => {
    const my = useUserStore()
    const token = my.token

    if (token) {
      config.headers.Authorization = `Bearer ${token.value}`
    }
    return config
  },
  (error) => Promise.reject(error),
)

export default api

//#region New API Plugin
// ✅ How it works

// Request interceptor (optional): attach the token.

// Response interceptor: catch 401, try refresh once, retry original request.

// Example (production-ready pattern):

// // attach token automatically
// axios.interceptors.request.use(config => {
//   const token = localStorage.getItem('access_token')
//   if (token) {
//     config.headers.Authorization = `Bearer ${token}`
//   }
//   return config
// })

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
