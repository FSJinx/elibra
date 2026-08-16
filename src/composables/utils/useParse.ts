
export function useParser() {
  return {
    toCapital(text: string | null) {
      return text ? text?.charAt(0).toUpperCase() + text.slice(1) : ''
    },

    status(status: string | null) {
      const stats: Record<string, Variants> = {
        // ====== SUCCESS =======
        active: 'success',

        // ====== ERROR =======
        inactive: 'danger',
      }

      return status ? stats[status] : undefined
    },
  }
}
