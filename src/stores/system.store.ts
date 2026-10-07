export const systemStore = defineStore(
  'system',
  () => {
    const sidebar = ref<boolean>(false)

    function toggleSidebar() {
      sidebar.value = !sidebar.value
    }

    return {
      // SETUPS
      sidebar,

      // ACTIONS
      toggleSidebar,
    }
  },
  {
    persist: {
      pick: ['sidebar'],
    },
  },
)
