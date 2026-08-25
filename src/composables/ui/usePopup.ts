import type { SweetAlertOptions } from 'sweetalert2'

const loadingGif = new URL('@/assets/icon/loading.gif', import.meta.url).href

export function usePopup() {
  const swal = useSwal()

  function fire(options: SweetAlertOptions = {}) {
    return swal.fire(options)
  }

  async function confirm(options: SweetAlertOptions = {}) {
    return await swal.fire({
      icon: 'question',
      title: 'Confirm',
      text: 'Are you sure?',
      showCancelButton: true,
      focusCancel: true,
      ...options,
    })
  }

  function success(message: string, title?: string) {
    return swal.fire({
      title: title ?? 'Success',
      text: message ?? 'Transaction successful, you can proceed now.',
      icon: 'success',
    })
  }

  function error(message: string, title?: string) {
    return swal.fire({
      title: title ?? 'Error',
      text: message ?? 'Transaction unsuccessful, please try again.',
      icon: 'error',
      confirmButtonColor: 'var(--color-danger)',
      confirmButtonText: 'Close',
    })
  }

  function warning(message: string, title?: string) {
    return swal.fire({
      title: title ?? 'Warning',
      text: message ?? 'Are you sure you want to proceed? This action might be irreversible.',
      icon: 'warning',
      confirmButtonColor: 'var(--color-warning)',
      confirmButtonText: 'Cancel',
    })
  }

  function info(message: string, title?: string) {
    return swal.fire({
      title: title ?? 'Info',
      text: message ?? 'Are you sure you want to proceed? This action might be irreversible.',
      icon: 'info',
      confirmButtonColor: 'var(--color-info)',
      confirmButtonText: 'Okay',
    })
  }

  function load(message?: string) {
    return swal.fire({
      title: 'Please wait',
      text: message ?? "We're loading it for you...",
      imageUrl: loadingGif,
      imageWidth: 72,
      imageHeight: 72,
      showConfirmButton: false,
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: () => {
        // swal.showLoading()
      },
      //   ...options,
    })
  }

  function unload() {
    swal.hideLoading()
    swal.close()
  }

  return {
    fire,
    confirm,

    success,
    error,
    warning,
    info,

    load,
    unload,
  }
}
