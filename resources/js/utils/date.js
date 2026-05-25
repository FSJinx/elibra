const formatDate = (format = 'full') => {
  const date = new Date()

  const formats = {
    short: {
      month: 'long',
      day: 'numeric',
    },

    full: {
      month: 'long',
      day: 'numeric',
      year: 'numeric',
    },

    withDay: {
      weekday: 'long',
      month: 'long',
      day: 'numeric',
    },

    complete: {
      weekday: 'long',
      month: 'long',
      day: 'numeric',
      year: 'numeric',
    },
  }

  return new Intl.DateTimeFormat('en-PH', formats[format]).format(date)
}

export default formatDate
