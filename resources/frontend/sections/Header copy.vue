<template>
  <header class="sticky top-0 border-b border-neutral-200 z-10 bg-white">
    <div class="flex justify-between p-5 mx-auto max-w-7xl">
      <div class="flex items-center h-full gap-3">
        <img :src="images.isu" alt="" class="h-11" />
        <div class="flex flex-col justify-between overflow-hidden whitespace-nowrap">
          <h1 class="text-2xl font-extrabold text-primary leading-6.5">e-Libra</h1>
          <p class="text-sm hidden sm:inline">The ISU-One Library Management System of the <span class="font-bold text-primary">ISABELA STATE UNIVERSITY</span></p>
          <p class="text-sm inline sm:hidden">The LMS of <span class="font-bold text-primary">ISABELA STATE UNIVERSITY</span></p>
        </div>
      </div>

      <div class="ml-auto flex gap-5 items-center">
        <div class="action-btns hidden md:flex gap-2 items-center" v-if="!my.token">
          <LoginButton />
          <Button type="solid" label="Register" color="primary" />
        </div>

        <button v-else @click="my.home" class="cursor-pointer">
          <UserCircle class="min-h-4 min-w-4 text-gray-600" />
        </button>
        <Button type="outline" icon="Menu" class="flex md:hidden py-3 hover:bg-primary hover:border-primary" @click="toggleSideMenu" />
      </div>
    </div>
    <div class="hidden sm:block bg-primary-dark">
      <div class="flex gap-2 mx-auto px-5 max-w-7xl text-sm text-white">
        <router-link :to="{ name: nav.link }" class="flex text-center p-3 hover:bg-primary transition-all duration-150" v-for="(nav, index) in navs" :key="index">
          {{ nav.name }}
        </router-link>
      </div>
    </div>
  </header>
</template>

<script setup>
import images from '@/assets/images'
import { reactive, ref } from 'vue'
import BannerComponent from '@/components/public/BannerComponent.vue'
import Button from '@/components/buttons/Button.vue'
import { authStore } from '@/stores/auth'
import LoginButton from '@/components/buttons/LoginButton.vue'

const my = authStore()

const sideMenu = reactive({
  show: false,
})

function toggleSideMenu() {
  sideMenu.show = !sideMenu.show
}

const navs = ref([
  {
    name: 'Home',
    link: 'App',
  },
  {
    name: 'Campuses',
    link: '',
  },
])
</script>

<style>
.navs a {
  outline: none;
}

.navs a:focus-within {
  color: var(--color-primary);
}
</style>
