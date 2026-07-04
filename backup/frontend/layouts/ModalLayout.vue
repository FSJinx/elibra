<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="modal-wrapper fixed inset-0 flex items-center justify-center bg-black/60 h-screen overflow-y-auto" @click.self="close">
        <div class="modal relative bg-white rounded-xl shadow-xl border border-gray-200 select-none" :class="[sizeClasses, positionClasses, modal.position]" ref="modalRef">
          <!-- <X class="absolute top-0 right-0 m-3" icon="X" @click="close" /> -->

          <!-- Modal Header -->
          <div class="p-5 border-b border-gray-300" v-if="$slots.header">
            <slot name="header" />
          </div>

          <div class="p-5">
            <slot />
          </div>

          <!-- Modal Footer -->
          <div class="py-3 px-5 border-t border-gray-300" v-if="$slots.footer">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
import Button from '@/components/buttons/Button.vue'
import { ref, computed, watch } from 'vue'

//#region Prop Definition
/**
 * @typedef {'center' | 'top' | 'bottom'} ModalPosition
 */

/**
 * @typedef {'small' | 'normal' | 'large' | 'xlarge' | '2xlarge' | 'full'} ModalSize
 */
//#endregion

const isOpen = ref(false)
const modalRef = ref(null)
const timer = ref(null)

const modal = defineProps({
  hasInputs: {
    type: Boolean,
    default: false,
  },
  position: {
    /** @type {import('vue').PropType<ModalPosition>} */
    type: String,
    default: 'center',
  },
  size: {
    /** @type {import('vue').PropType<ModalSize>} */
    type: String,
    default: 'normal',
  },
})

//#region Dynamic Classes Configuration
const positionClasses = computed(() => {
  const positions = {
    top: 'mb-auto', // May kaunting space sa taas
    center: 'my-auto max-h-[90vh] overflow-y-auto',
    bottom: 'mt-auto', // May kaunting space sa baba
  }
  return positions[modal.position] || positions.center
})

const sizeClasses = computed(() => {
  const sizes = {
    small: 'w-full sm:max-w-100',
    normal: 'w-full sm:max-w-130',
    large: 'w-full sm:max-w-200',
    xlarge: 'w-full sm:max-w-250',
    '2xlarge': 'w-full sm:max-w-300',
    full: 'max-w-[95vw] w-full h-[90vh]', // Halos occupy ang buong screen
  }
  return sizes[modal.size] || sizes.normal
})
//#endregion

//#region State Controller
const open = () => {
  isOpen.value = true
}

const close = () => {
  if (modal.hasInputs) {
    const el = modalRef.value
    if (el) {
      if (timer.value) clearTimeout(timer)
      el.classList.add('shake')

      timer.value = setTimeout(() => {
        el.classList.remove('shake')
      }, 500)
    }
    return
  }
  isOpen.value = false
}
//#endregion

watch(isOpen, (newValue) => {
  if (newValue) {
    // Kapag bukas ang modal, i-lock ang body scroll
    document.body.style.overflow = 'hidden'
  } else {
    // Kapag sarado, ibalik sa normal
    document.body.style.removeProperty('overflow')
  }
})

defineExpose({ open, close })
</script>

<style scoped>
.modal-wrapper {
  scrollbar-width: none !important;
}
/* 1. Base Transition para sa Wrapper at Modal */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}

/* 2. Start at End States ng Wrapper (Fade) */
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.fade-leave-to {
  transition-delay: 0.1s;
}

.fade-enter-active .modal,
.fade-leave-active .modal {
  transition:
    transform 0.25s var(--ease-bounce),
    opacity 0.25s ease;
}

/* 3. Start at End States ng Inner Modal (Slide + Fade) */
.fade-enter-from .modal.top,
.fade-leave-to .modal.top {
  opacity: 0;
  transform: translateY(-20px);
}
.fade-enter-from .modal.center,
.fade-leave-to .modal.center {
  opacity: 0;
  transform: scale(0.5);
}
.fade-enter-from .modal.bottom,
.fade-leave-to .modal.bottom {
  opacity: 0;
  transform: translateY(20px);
}

.shake {
  animation: shakeEffect 0.5s ease-in-out;
}

@keyframes shakeEffect {
  0%,
  100% {
    margin-left: 0px;
    margin-right: 0px;
  }
  10%,
  30%,
  50%,
  70%,
  90% {
    margin-left: -8px;
    margin-right: 8px;
  }
  20%,
  40%,
  60%,
  80% {
    margin-left: 8px;
    margin-right: -8px;
  }
}
</style>
