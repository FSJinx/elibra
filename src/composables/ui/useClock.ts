export const useClock = () => {
  const today = ref<string>('')
  let timer: ReturnType<typeof setInterval> | null = null

  const updateDate = () => {
    const now = new Date()

    const date = now.toLocaleDateString('en-US', {
      month: 'long',
      day: 'numeric',
      year: 'numeric',
    })

    const time = now.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: '2-digit',
      second: '2-digit',
    })

    today.value = `Today, ${date} - ${time}`
  }

  onMounted(() => {
    updateDate()
    timer = setInterval(updateDate, 1000)
  })

  onUnmounted(() => {
    if (timer) clearInterval(timer)
  })

  return {
    today,
  }
}
