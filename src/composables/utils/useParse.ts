export function useParser() {
  return {
    toCapital(text: string | null) {
      return text ? text?.charAt(0).toUpperCase() + text.slice(1) : ''
    },

    status(status: string | null) {
      const stats: Record<string, Variants> = {
        // ====== SUCCESS =======
        available: 'success',
        active: 'success',
        admin: 'success',

        // ====== INFO =======
        librarian: 'info',
        reserved: 'info',
        book: 'info',

        // ====== WARNING =======
        serial: 'warning',

        // ====== ERROR =======
        inactive: 'danger',
        borrowed: 'danger',

        // ====== RESTORE =======
        patron: 'restore',
        academic: 'restore',

        default: 'default',
      }

      return status ? stats[status ?? 'default'] : 'default'
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
