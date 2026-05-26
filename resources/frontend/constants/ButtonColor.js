const colors = {
  blue: {
    solid: 'bg-blue-600 text-white border-blue-600 hover:bg-blue-700',
    soft: 'bg-blue-100 text-blue-600 border-blue-100 hover:bg-blue-200',
    outline: 'bg-white text-blue-600 border-blue-600 hover:bg-blue-50',
  },

  red: {
    solid: 'bg-red-600 text-white border-red-600 hover:bg-red-700',
    soft: 'bg-red-100 text-red-600 border-red-100 hover:bg-red-200',
    outline: 'bg-white text-red-600 border-red-600 hover:bg-red-50',
  },

  green: {
    solid: 'bg-green-600 text-white border-green-600 hover:bg-green-700',
    soft: 'bg-green-100 text-green-600 border-green-100 hover:bg-green-200',
    outline: 'bg-white text-green-600 border-green-600 hover:bg-green-50',
  },

  purple: {
    solid: 'bg-purple-600 text-white border-purple-600 hover:bg-purple-700',
    soft: 'bg-purple-100 text-purple-600 border-purple-100 hover:bg-purple-200',
    outline: 'bg-white text-purple-600 border-purple-600 hover:bg-purple-50',
  },

  yellow: {
    solid: 'bg-yellow-500 text-white border-yellow-500 hover:bg-yellow-600',
    soft: 'bg-yellow-100 text-yellow-700 border-yellow-100 hover:bg-yellow-200',
    outline: 'bg-white text-yellow-600 border-yellow-500 hover:bg-yellow-50',
  },

  default: {
    solid: 'bg-gray-600 text-white border-gray-600 hover:bg-gray-700',
    soft: 'bg-gray-100 text-gray-700 border-gray-100 hover:bg-gray-200',
    outline: 'bg-white text-gray-600 border-gray-600 hover:bg-gray-50',
  },
}

const variant = (color, type) => {
  return colors[color][type] || colors.default[type]
}

export default variant
