<template>
  <aside class="flex flex-col h-screen min-w-75 bg-white relative border-r border-gray-100">
    <div class="flex items-center gap-2 border-b h-15 p-4" :class="[border]">
      <BannerComponent logo-height="h-7" font-size="text-xl" />
    </div>

    <!-- Sidebar Menu -->
    <div class="sidebar overflow-auto">
      <div class="flex flex-col pt-1" v-for="(sidebar, index) in menu[my.role]" :key="index">
        <!-- Menu Title -->
        <h1 class="font-semibold uppercase text-xs text-neutral-400 m-2 my-3">{{ sidebar.name }}</h1>

        <!-- Menu Items -->
        <div class="px-3">
          <router-link v-slot="{ isActive }" :to="{ name: child.path }" class="flex items-center p-4 pl-2 gap-3 rounded-sm cursor-pointer hover:bg-secondary/5 hover:border-primary transition-all duration-50" active-class=" border-l-3 border-primary bg-primary/5" v-for="(child, index) in sidebar.children" :key="child.name">
            <component :is="child.icon" class="h-5 w-5"></component>
            <p class="text-sm">{{ child.name }}</p>
            <ArrowLeft class="h-4 w-4 ml-auto" stroke-width="1" v-if="isActive" />
          </router-link>
        </div>
      </div>
    </div>

    <!-- Bottom Toggle Button -->
    <div class="h-21 border-t mt-auto z-2 bg-white" :class="[pad, border]">
      <span class="flex h-full items-center text-lg gap-2 rounded cursor-pointer select-none hover:bg-gray-200" :class="[pad]" @click="mini.toggleMini()">
        <img :src="images.isu" alt="" class="h-8 w-auto" />
        <span class="flex flex-col justify-center w-[80%] gap-0.5">
          <p class="text-sm text-ellipsis whitespace-nowrap overflow-hidden w-[90%]">{{ my.fullname }}</p>
          <p class="text-xs text-neutral-400">{{ roleMap[my.role].long }}</p>
        </span>
        <ChevronUp class="ml-auto transition duration-200" :class="[state.show ? 'rotate-180' : '']" />
      </span>
    </div>

    <!-- Mini Popup -->
    <Transition name="mini">
      <div class="flex items-end pb-21 justify-center h-full w-full absolute bottom-0 left-0 bg-black/20 z-1" :class="[pad]" v-if="state.show" @click="mini.toggleMini()">
        <div class="mini-card w-full bg-white rounded-lg p-2 mb-3 transform" @click.stop>
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
  </aside>
</template>

<script setup>
import images from '@/assets/images'
import BannerComponent from '@/components/public/BannerComponent.vue'
import { roleMap } from '@/constants/roleMap'
import menu from '@/constants/sidebar'
import { useUserStore } from '@/stores/auth'
import { reactive, ref } from 'vue'

const pad = ref('px-3 py-4')
const border = ref('border-gray-200')
const my = useUserStore()

const state = reactive({
  show: false,
})

const mini = {
  state,

  toggleMini() {
    state.show = !state.show
  },
}
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
