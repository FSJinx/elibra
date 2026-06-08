<template>
  <aside class="flex flex-col h-screen min-w-75 bg-white relative border-r border-gray-100">
    <div class="flex items-center gap-2 border-b h-15 p-4" :class="[border]">
      <BannerComponent logo-height="h-7" font-size="text-xl" />
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar overflow-y-auto">
      <div class="flex flex-col py-1" v-for="(sidebar, index) in menu[my.role]" :key="index">
        <!-- Menu Title -->
        <h1 class="font-semibold uppercase text-xs text-neutral-400 m-2 my-3">{{ sidebar.name }}</h1>

        <!-- Menu Items -->
        <div class="px-3">
          <router-link v-slot="{ isActive }" :to="{ name: child.path }" class="flex items-center p-4 pl-2 gap-3 rounded-sm cursor-pointer hover:bg-secondary/5 hover:border-primary transition-all duration-50" active-class="bg-secondary/10 " v-for="(child, index) in sidebar.children" :key="child.name">
            <Transition name="activeArrow">
              <ArrowRight class="h-4 w-4" stroke-width="1.5" v-if="isActive" />
            </Transition>
            <component :is="child.icon" class="h-5 w-5"></component>
            <p class="text-sm">{{ child.name }}</p>
          </router-link>
        </div>
      </div>
    </div>

    <!-- Bottom Toggle Button -->
    <div class="h-21 border-t mt-auto z-2 bg-white p-3" :class="[border]">
      <span class="flex h-full items-center text-lg gap-2 p-3 rounded cursor-pointer select-none hover:bg-gray-200" @click="mini.toggleMini()" ref="miniBtn">
        <img :src="images.isu" alt="" class="h-8 w-auto" />
        <span class="flex flex-col justify-center w-[80%] gap-0.5">
          <p class="text-sm text-ellipsis whitespace-nowrap overflow-hidden capitalize w-[90%]">{{ my.fullName }}</p>
          <p class="text-xs text-neutral-400">{{ roleMap[my.role]?.long }}</p>
        </span>
        <ChevronUp class="ml-auto transition duration-200" :class="[state.show ? 'rotate-180' : '']" />
      </span>
    </div>

    <!-- Mini Popup -->
    <Transition name="mini">
      <div class="flex items-end pb-21 justify-center h-full w-full absolute bottom-0 left-0 bg-black/20 z-1" :class="[pad]" v-if="state.show">
        <div class="mini-card flex flex-col gap-2 w-full bg-white rounded-lg p-2 mb-3 transform" ref="miniRef">
          <div class="flex flex-col items-center justify-center gap-2 py-4">
            <img :src="images.isu" alt="" class="h-20 w-20" />
            <p class="text-sm font-bold capitalize">{{ my.fullName }}</p>
            <p class="text-xs">{{ `@${my.username ?? 'No username'}  |  ${roleMap[my.role].short}` }}</p>
          </div>
          <span class="flex items-center w-full gap-3 p-3 rounded cursor-pointer hover:bg-gray-200">
            <UserCircle class="h-5 w-5" />
            <p class="text-sm">Profile</p>
          </span>
          <span class="flex items-center w-full gap-3 p-3 rounded cursor-pointer hover:bg-gray-200">
            <Settings class="h-5 w-5" />
            <p class="text-sm">Settings</p>
          </span>
          <span class="flex items-center w-full gap-3 p-3 rounded cursor-pointer border border-red-500 text-red-500 hover:bg-red-500 hover:text-white">
            <DoorOpen class="h-5 w-5" />
            <p class="text-sm">Logout</p>
          </span>
        </div>
      </div>
    </Transition>
  </aside>
</template>

<script setup>
import images from '@/assets/images'
import BannerComponent from '@/components/public/BannerComponent.vue'
import menu from '@/constants/sidebar'

import { roleMap } from '@/constants/roleMap'
import { useUserStore } from '@/stores/auth'
import { onClickOutside } from '@vueuse/core'
import { reactive, ref } from 'vue'

const pad = ref('px-3 py-4')
const border = ref('border-gray-200')
const my = useUserStore()

const miniRef = ref(null)
const miniBtn = ref(null)

const state = reactive({
  show: false,
})

const mini = {
  state,

  toggleMini() {
    state.show = !state.show
  },

  closeMini() {
    state.show = false
  },
}

onClickOutside(
  miniRef,
  () => {
    if (state.show) mini.closeMini()
  },
  { ignore: [miniBtn] },
)
</script>

<style>
::-webkit-scrollbar {
  width: 0;
}

.mini-card span {
  transition: all 0.1s ease;
}

/* Overlay */
.mini-enter-active,
.mini-leave-active {
  transition: opacity 0.2s ease;
}

.mini-enter-from,
.mini-leave-to {
  opacity: 0;
}

.mini-enter-to,
.mini-leave-from {
  opacity: 1;
}

.activeArrow-enter-active,
.activeArrow-leave-active {
  transition: all 0.2s ease;
}

.activeArrow-enter-from,
.activeArrow-leave-to {
  opacity: 0;
  transform: translateX(-100%);
}

.activeArrow-leave-to {
  transition-delay: opacity 0.2s ease;
}

.activeArrow-enter-to,
.activeArrow-leave-from {
  opacity: 1;
  transform: translateX(0);
}

.mini-enter-active .mini-card,
.mini-leave-active .mini-card {
  transition:
    transform 0.2s var(--ease-bounce),
    opacity 0.2s ease,
    height 0.2s ease;
}

.mini-enter-from .mini-card,
.mini-leave-to .mini-card {
  opacity: 0;
  transform: scale(0.4) translateY(200%);
}

.mini-enter-to .mini-card,
.mini-leave-from .mini-card {
  opacity: 1;
  transform: scale(1) translateY(0);
}
</style>
