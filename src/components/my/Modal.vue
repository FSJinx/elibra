<template>
  <Teleport to="body">
    <Transition name="fade">
      <div v-if="isOpen" class="modal-wrapper fixed inset-0 flex items-center justify-center bg-backdrop h-dvh p-5" :class="[hasInputs ? '' : 'cursor-pointer']" @click.self="close">
        <div class="modal relative bg-background rounded-xl shadow-2xl border border-border cursor-default overflow-hidden" :class="[sizeClasses, positionClasses, position]" ref="modalRef">
          <span class="absolute top-5 right-5 ml-auto text-lg text-foreground/25 hover:text-foreground cursor-pointer transition-all duration-200">
            <Icon icon="x-lg" @click="close" v-if="!disableCloseBtn" style="-webkit-text-stroke: 1px" />
          </span>

          <!-- Modal Body
          <div class="flex flex-col overflow-y-auto transition-all duration-200">
            <Transition name="fade">
              <p class="text-danger p-3 px-5" v-if="errorMessage?.length > 0 && !loading"><Icon class="mr-2" icon="exclamation-circle" /> {{ errorMessage }}</p>
            </Transition>
            <div class="flex-1 flex min-h-100" v-if="loading">
              <Spinner class="m-auto text-2xl" />
            </div>
            <slot v-else />
          </div> -->

          <slot />
        </div>
      </div>
    </Transition>
  </Teleport>
</template>
<script setup lang="ts">
type ModalPosition = 'top' | 'center' | 'bottom'
type ModalSize = 'small' | 'normal' | 'large' | 'xlarge' | '2xlarge' | 'full'

interface Props {
  hasInputs?: boolean
  position?: ModalPosition
  size?: ModalSize
  disableCloseBtn?: boolean
  loading?: boolean
  error?: string
}

const props = withDefaults(defineProps<Props>(), {
  hasInputs: false,
  position: 'center',
  size: 'normal',
  disableCloseBtn: false,
})

const emit = defineEmits(['show', 'closing'])

const isOpen = ref(false)
const modalRef = ref<HTMLElement | null>(null)
const timer = ref<ReturnType<typeof setTimeout> | null>(null)
const errorMessage = ref(props.error ?? '')

const positionClasses = computed(() => {
  const positions: Record<ModalPosition, string> = {
    top: 'mb-auto',
    center: 'my-auto max-h-[90vh]',
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
    '2xlarge': 'w-full sm:max-w-400',
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

    errorMessage.value = "You can't close this modal yet because it has inputs. Please clear inputs before closing."

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

provide('modal', {
  buttonDisabled: props.disableCloseBtn,
})

watch(isOpen, (opened) => {
  if (opened) {
    document.body.style.overflow = 'hidden'
  } else {
    document.body.style.removeProperty('overflow')
  }

  emit('show', isOpen.value)
})

watch(
  () => props.hasInputs,
  () => {
    errorMessage.value = ''
  },
)

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
