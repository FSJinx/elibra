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

    formatDate(dateString: string | null) {
      if (!dateString) return 'N/A'
      return new Date(dateString).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      })
    },
  }
}
