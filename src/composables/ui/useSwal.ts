import Swal from 'sweetalert2'
import 'sweetalert2/dist/sweetalert2.min.css'

export const useSwal = () => {
  const swalWithDefaults = Swal.mixin({
    backdrop: 'var(--color-backdrop)',
    padding: '1.5rem 3rem',
    customClass: {
      popup: 'elpop-container',
    },

    // Inside Container
    background: 'var(--color-container)',
    color: 'var(--color-foreground)',

    // Confirm Button
    confirmButtonColor: 'var(--color-success)',
    confirmButtonText: 'Confirm',

    // Cancel Button
    cancelButtonColor: 'var(--color-danger)',
    cancelButtonText: 'Cancel',

    // STATES AND ACTIONS
    reverseButtons: true,
  })

  return swalWithDefaults
}
