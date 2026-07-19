import api from '@/plugins/axios'

export function useAuth() {
  const auth = authStore()
  return {
    async login({ username = null, password = null }: { username?: string | null; password?: string | null } = {}) {
      console.log(username)
      console.log(password)

      try {
        const response = await api.post('auth/login', {
          username: username,
          password: password,
        })

        const token = response.data?.data?.token

        if (!token) {
          throw new Error('Login succeeded but no token was returned.')
        }

        await auth.setToken(token)
        await auth.getUser()

        //   if (!rememberMe.value) {
        //     localStorage.removeItem('token')
        //   }
      } catch (error) {
        //   const response = error as { response?: { data?: LoginErrorResponse } }
        //   errorMessage.value = response.response?.data?.message || 'Login failed. Please check your credentials and try again.'
      } finally {
        await auth.home()
        //   isSubmitting.value = false
      }
    },
  }
}
