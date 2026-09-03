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

    timeAgo(datetime: string) {
      const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' })
      const diffMs = new Date(datetime).getTime() - Date.now()
      const diffSec = Math.round(diffMs / 1000)
      const diffMin = Math.round(diffSec / 60)
      const diffHour = Math.round(diffMin / 60)
      const diffDay = Math.round(diffHour / 24)

      if (Math.abs(diffSec) < 60) return rtf.format(diffSec, 'second')
      if (Math.abs(diffMin) < 60) return rtf.format(diffMin, 'minute')
      if (Math.abs(diffHour) < 24) return rtf.format(diffHour, 'hour')
      return rtf.format(diffDay, 'day')
    },
  }
}
