<template>
  <teleport to="body">
    <transition name="popup">
      <div v-if="popup.show" class="popup-wrapper elpop fixed inset-0 flex overflow-hidden bg-black/50" @click="popup.type === 'load' ? '' : elpop.close()">
        <div class="popup-card relative flex flex-col justify-center items-center w-100 m-auto gap-3 h-max p-5 sm:w-125 rounded-lg bg-white shadow-xl overflow-hidden -translate-y-8 sm:-translate-y-5" :class="cardDesign" @click.stop>
          <div class="flex flex-col justify-center text-center h-38">
            <!-- TYPE -->
            <div class="flex justify-center text-xl font-bold w-full m-auto">
              <img :src="images.logo" alt="" class="h-auto w-20" v-if="popup.type === 'load'" />
              <img :src="elpopIcon[popup.type]" alt="" class="h-20 w-20" v-else />
            </div>

            <!-- TITLE -->
            <h2 class="text-2xl font-semibold">
              {{ popup.title }}
            </h2>
          </div>

          <!-- MESSAGE -->
          <p class="text-gray-500 text-center" v-if="popup.message">
            {{ popup.message }}
          </p>

          <loading-animation v-if="popup.type === 'load'" />

          <div class="flex justify-center gap-2 mt-2" v-if="popup.withButtons">
            <Button label="Close" type="solid" color="red" @click="elpop.close()" />
          </div>

          <div class="w-full h-1 absolute bottom-0 left-0 timer" :class="bgColor[popup.type]" v-if="popup.duration > 0" :style="{ animationDuration: `${popup.duration - 100}ms` }"></div>
        </div>
      </div>
    </transition>
  </teleport>
</template>

<script setup>
import images from '@/assets/images/index.js'
import LoadingAnimation from '../animations/LoadingAnimation.vue'
import elpopIcon from '@/assets/popups/index.js'
import elpop from '@/plugins/elpop.js'
import { computed, ref } from 'vue'
import Button from '../buttons/Button.vue'

const popup = elpop.state

const bgColor = ref({
  success: 'bg-green-500',
  error: 'bg-red-500',
  info: 'bg-blue-500',
  warning: 'bg-yellow-500',
})

const cardDesign = computed(() => {
  let card = ''
  if (popup.withButtons || popup.type === 'load') {
    switch (popup.type) {
      case 'success':
        card += 'border-b-5 border-green-500'
        break
      case 'error':
        card += 'border-b-5 border-red-500'
        break
      case 'load':
        card += 'border-b-5 border-green-600'
        break

      default:
        break
    }
  }

  return card
})
</script>

<style scoped>
.popup-enter-active,
.popup-leave-active {
  transition: opacity 0.2s ease;
}

.popup-leave-active {
  transition-delay: 0.05s;
}

.popup-enter-from,
.popup-leave-to {
  opacity: 0;
}

.popup-enter-active .popup-card,
.popup-leave-active .popup-card {
  transition:
    transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
    opacity 0.2s ease;
}

.popup-enter-from .popup-card,
.popup-leave-to .popup-card {
  opacity: 0;
  transform: scale(0.5);
}

@keyframes timer {
  from {
    width: 100%;
  }

  to {
    width: 0%;
  }
}

.timer {
  animation-name: timer;
  animation-timing-function: linear;
  animation-fill-mode: forwards;
}
</style>
