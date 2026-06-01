const formatTime = (format = 'standard') => {
  const date = new Date()

  const formats = {
    short: {
      hour: 'numeric',
      minute: '2-digit',
    },

    standard: {
      hour: 'numeric',
      minute: '2-digit',
      hour12: true,
    },

    seconds: {
      hour: 'numeric',
      minute: '2-digit',
      second: '2-digit',
      hour12: true,
    },

    military: {
      hour: '2-digit',
      minute: '2-digit',
      hour12: false,
    },

    full: {
      hour: 'numeric',
      minute: '2-digit',
      second: '2-digit',
      hour12: true,
      //   timeZoneName: 'short',
    },
  }

  return new Intl.DateTimeFormat('en-PH', formats[format]).format(date)
}

export default formatTime
