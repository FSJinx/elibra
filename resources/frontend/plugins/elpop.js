import { reactive, ref } from 'vue'

/**
 * @typedef { 'info' | 'success' | 'error' | 'warning' | 'load' } PopupType
 */

/**
 * @typedef PopupOptions
 * @property {string} [title]
 * @property {string} [message]
 */

const state = reactive({
  show: false,
  type: '',
  title: '',
  message: '',
  withButtons: null,
  duration: 0,
})

const elpop = {
  state,
  popupDuration: null,

  /**
   * @param {PopupType} type
   * @param {PopupOptions} options
   */

  pop(type, options = {}) {
    this.open()
    state.type = type ?? 'info'
    state.title = options.title ?? ''
    state.message = options.message ?? ''
    state.withButtons = options.withButtons || false
    state.duration = options.duration || 0
  },

  //   State

  close() {
    state.show = false
    state.title = ''
    state.message = ''
    state.withButtons = null
    state.duration = 0
    clearTimeout(this.popupDuration)

    document.body.style.overflow = ''
  },

  open() {
    state.show = true

    document.body.style.overflow = 'hidden'
  },

  // Statics

  load(message = 'Loading...') {
    this.close()
    this.pop('load', {
      title: 'Please wait',
      message,
    })
  },

  showSuccess(message = 'Success', timer = 2000) {
    this.close()

    this.pop('success', {
      title: 'Success',
      message,
      duration: timer,
    })

    this.popupDuration = setTimeout(() => {
      this.close()
      this.popupDuration = null
    }, timer)
  },

  //   Dynamics

  confirm(message = 'Please confirm submission') {
    this.close()
    this.pop('success', {
      title: 'Confirm',
      message,
      withButtons: true,
    })
  },

  confirmLogout() {
    this.close()
    this.pop('error', {
      title: 'Logout',
      message: 'Are you sure you want to logout?',
      withButtons: true,
    })
  },
}

export default elpop
