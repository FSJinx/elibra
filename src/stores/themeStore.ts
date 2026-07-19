interface SystemState {
  name: string
  theme: 'light' | 'dark'
}

export const useSystemStore = defineStore(
  'system',
  () => {
    const system = ref<SystemState>({
      name: 'e-Libra',
      theme: 'light',
    })

    function setTheme(theme: SystemState['theme']) {
      system.value.theme = theme
    }

    function toggleTheme() {
      system.value.theme = system.value.theme === 'light' ? 'dark' : 'light'
    }

    return {
      system,
      setTheme,
      toggleTheme,
    }
  },
  {
    persist: {
      pick: ['system.value.theme'],
    },
  },
)
