import Confirm from '@/components/alerts/Confirm.vue'
import Success from '@/components/alerts/static/Success.vue'
import { usePopup } from '@/stores/popup'

const popup = usePopup()

// Static Alerts

export const success = ({ message = null }) => {
  popup.open(Success, {
    message,
  })
}

export const confirm = ({ title = null, message = null }) => {
  popup.open(Confirm, {
    title,
    message,
  })
}
