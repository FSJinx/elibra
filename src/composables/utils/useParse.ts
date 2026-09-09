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
        super_admin: 'restore',

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

      if (!Number.isFinite(diffMs)) return 'Invalid date'

      const diffSec = Math.round(diffMs / 1000)
      const diffMin = Math.round(diffSec / 60)
      const diffHour = Math.round(diffMin / 60)
      const diffDay = Math.round(diffHour / 24)

      if (Math.abs(diffSec) < 60) return rtf.format(diffSec, 'second')
      if (Math.abs(diffMin) < 60) return rtf.format(diffMin, 'minute')
      if (Math.abs(diffHour) < 24) return rtf.format(diffHour, 'hour')
      return rtf.format(diffDay, 'day')
    },

    date(dateValue: string) {
      const rtf = new Intl.RelativeTimeFormat('en', { numeric: 'auto' })
      const match = /^(\d{4})-(\d{2})-(\d{2})$/.exec(dateValue)

      if (!match) return 'Invalid date'

      const [, year, month, day] = match
      const target = new Date(Number(year), Number(month) - 1, Number(day))
      const now = new Date()

      if (Number.isNaN(target.getTime())) return 'Invalid date'

      let months = (now.getFullYear() - target.getFullYear()) * 12 + now.getMonth() - target.getMonth()
      if (now.getDate() < target.getDate()) months -= 1

      if (Math.abs(months) >= 1) return rtf.format(-months, 'month')

      const targetDay = new Date(target.getFullYear(), target.getMonth(), target.getDate())
      const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
      const days = Math.round((today.getTime() - targetDay.getTime()) / 86400000)

      return rtf.format(-days, 'day')
    },

    dateTimeAgo(value: string) {
      const parser = useParser()

      return /^\d{4}-\d{2}-\d{2}$/.test(value) ? parser.date(value) : parser.timeAgo(value)
    },

    toMoney(value: number | string | null | undefined) {
      const val = Number(value)

      return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP',
      }).format(Number.isFinite(val) ? val : 0)
    },
  }
}
