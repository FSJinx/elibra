interface ErrorState {
  show: boolean
  code: string | number
  title: string
  message: string
  path: string
}

// Global state shared across the application
const state = reactive<ErrorState>({
  show: false,
  code: '',
  title: '',
  message: '',
  path: '',
})

export const useError = () => {
  const route = useRoute()
  const auth = useAuth()

  function open(options?: Partial<Omit<ErrorState, 'show'>>) {
    if (options) {
      state.code = options.code ?? ''
      state.path = options.path ?? ''
      state.title = options.title ?? ''
      state.message = options.message ?? ''
    }
    state.show = true
    if (typeof document !== 'undefined') {
      document.body.style.overflow = 'hidden'
    }
  }

  function notFound(fullPath?: string) {
    open({
      code: '404',
      title: 'Page Not Found',
      path: fullPath,
      message: "The page you're looking for is either under going maintenance, under construction, moved, or not existing. If you think this is an error, please contact your administrator.",
    })
  }

  function forbidden() {
    open({
      code: '403',
      title: 'Access Denied',
      message: "You don't have the required permissions to access this page, please try again later. If you think this is an error, please contact your administrator.",
    })
  }

  function maintenance() {
    open({
      code: '503',
      title: 'Service Unavailable',
      message: 'This page is currently under maintenance. Please try again later.',
    })
  }

  function close() {
    state.show = false
    if (typeof document !== 'undefined') {
      document.body.style.overflow = ''
    }

    const previousLocation = route.redirectedFrom ?? null

    if (previousLocation) {
      const resolved = router.resolve(previousLocation)

      if (resolved && resolved.name && resolved.name !== route.name) {
        router.back()
        return
      }
    } else {
      auth.goHome()
    }
  }

  return {
    // Expose state as read-only to prevent direct mutation outside the composable
    state,

    open,
    close,
    forbidden,
    maintenance,
    notFound,
  }
}

export default useError
