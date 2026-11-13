export function formatDateTime(
  date: string | null | undefined
): string {
  if (!date) return '-'

  const parsedDate = new Date(date)

  if (isNaN(parsedDate.getTime())) return '-'

  return new Intl.DateTimeFormat('en-PH', {
    timeZone: 'Asia/Manila',
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    hour12: true,
  }).format(parsedDate)
}