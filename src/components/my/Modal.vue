<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="modal-wrapper fixed inset-0 flex items-center justify-center bg-backdrop h-screen overflow-y-auto p-10" :class="[hasInputs ? '' : 'cursor-pointer']" @click.self="close">
        <div class="modal relative bg-background rounded-2xl shadow-2xl border border-border select-none" :class="[sizeClasses, positionClasses, position]" ref="modalRef">
          <!-- <X class="absolute top-0 right-0 m-3" icon="X" @click="close" /> -->

          <!-- Modal Header -->
          <div class="p-5 py-4 border-b border-gray-300" v-if="$slots.header">
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
<script setup lang="ts">
import { computed, ref, watch } from 'vue'

type ModalPosition = 'top' | 'center' | 'bottom'
type ModalSize = 'small' | 'normal' | 'large' | 'xlarge' | '2xlarge' | 'full'

interface Props {
  hasInputs?: boolean
  position?: ModalPosition
  size?: ModalSize
}

const props = withDefaults(defineProps<Props>(), {
  hasInputs: false,
  position: 'center',
  size: 'normal',
})

const isOpen = ref(false)
const modalRef = ref<HTMLElement | null>(null)
const timer = ref<ReturnType<typeof setTimeout> | null>(null)

const positionClasses = computed(() => {
  const positions: Record<ModalPosition, string> = {
    top: 'mb-auto',
    center: 'my-auto max-h-[90vh] overflow-y-auto',
    bottom: 'mt-auto',
  }

  return positions[props.position]
})

const sizeClasses = computed(() => {
  const sizes: Record<ModalSize, string> = {
    small: 'w-full sm:max-w-100',
    normal: 'w-full sm:max-w-150',
    large: 'w-full sm:max-w-200',
    xlarge: 'w-full sm:max-w-250',
    '2xlarge': 'w-full sm:max-w-300',
    full: 'w-full max-w-[95vw] h-[90vh]',
  }

  return sizes[props.size]
})

function open() {
  isOpen.value = true
}

function close() {
  if (props.hasInputs) {
    const el = modalRef.value

    if (el) {
      if (timer.value) {
        clearTimeout(timer.value)
      }

      el.classList.add('shake')

      timer.value = setTimeout(() => {
        el.classList.remove('shake')
      }, 500)
    }

    return
  }

  isOpen.value = false
}

watch(isOpen, (opened) => {
  if (opened) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.removeProperty('overflow')
  }
})

defineExpose({
  open,
  close,
})
</script>

<style scoped>
.modal-wrapper {
  scrollbar-width: none !important;
}
/* 1. Base Transition para sa Wrapper at Modal */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.15s ease;
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
