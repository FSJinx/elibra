<template>
  <aside class="flex flex-col h-screen w-75 bg-white relative">
    <Transition name="mini">
      <div class="flex items-end justify-center h-full w-full absolute bottom-0 left-0 bg-black/20 z-1" :class="[pad]" v-if="state.show" @click="mini.toggleMini()">
        <div class="mini-card w-full bg-white rounded-lg p-2 mb-20 transform">
          <div class="flex flex-col items-center justify-center gap-2 py-4">
            <img :src="images.isu" alt="" class="h-20 w-20" />
            <p class="text-sm font-bold">Administrator</p>
          </div>
          <span class="flex items-center w-full gap-3 rounded cursor-pointer hover:bg-green-100" :class="[pad]">
            <UserCircle class="h-5 w-5" />
            <p class="text-sm">Profile</p>
          </span>
          <span class="flex items-center w-full gap-3 rounded cursor-pointer hover:bg-green-100" :class="[pad]">
            <Settings class="h-5 w-5" />
            <p class="text-sm">Settings</p>
          </span>
        </div>
      </div>
    </Transition>

    <div class="flex items-center gap-2 border-b" :class="[pad, border]">
      <img :src="images.logo" alt="" class="h-9 w-auto" />
      <h1 class="font-extrabold text-primary text-2xl">e-Libra</h1>
    </div>

    <div class="sidebar overflow-auto mb-5">
      <div class="flex flex-col" :class="[pad]" v-for="(sidebar, index) in adminSidebar" :key="index">
        <h1 class="font-semibold uppercase text-xs text-neutral-400 mb-3">{{ sidebar.name }}</h1>
        <div class="flex flex-col gap-2" v-for="(child, index) in sidebar.children" :key="child.name">
          <span class="flex items-center gap-3 rounded cursor-pointer hover:bg-green-100" :class="[pad]">
            <component :is="child.icon" class="h-5 w-5"></component>
            <p class="text-sm">{{ child.name }}</p>
          </span>
        </div>
      </div>
    </div>

    <div class="border-t mt-auto z-2 bg-white" :class="[pad, border]">
      <span class="flex items-center text-lg gap-2 rounded cursor-pointer select-none hover:bg-green-100" :class="[pad]" @click="mini.toggleMini()">
        <img :src="images.isu" alt="" class="h-8 w-auto" />
        <span class="flex flex-col justify-center">
          <p class="text-sm text-ellipsis whitespace-nowrap overflow-hidden w-[90%]">Reignromar Chryzel Balico</p>
          <p class="text-xs text-neutral-400">Admin</p>
        </span>
        <ChevronUp class="ml-auto transition duration-200" :class="[state.show ? 'rotate-180' : '']" />
      </span>
    </div>
  </aside>
</template>

<script setup>
import images from '@/assets/images'
import adminSidebar from '@/constants/adminSidebar'
import { reactive, ref } from 'vue'

const state = reactive({
  show: false,
})

const mini = {
  state,

  toggleMini() {
    state.show = !state.show
  },
}

const pad = ref('p-3')
const border = ref('border-gray-200')
</script>

<style>
::-webkit-scrollbar {
  width: 0;
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

.mini-enter-active .mini-card,
.mini-leave-active .mini-card {
  transition:
    transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1),
    opacity 0.2s ease,
    height 0.2s ease;
}

.mini-enter-from .mini-card,
.mini-leave-to .mini-card {
  opacity: 0;
  transform: scale(0.5) translateY(50px);
}

.mini-enter-to .mini-card,
.mini-leave-from .mini-card {
  opacity: 1;
  transform: scale(1) translateY(0);
}
</style>
