<template>
  <header class="fixed inset-0 w-full h-18 top-0 bg-white shadow-xs z-10">
    <div class="relative flex h-full w-full justify-center items-center px-5 sm:px-8">
      <BannerComponent :with-label="true" :with-logo="true" logo-height="h-7" font-size="text-xl" />
  
      <div class="absolute navs hidden md:flex items-end gap-2">
        <router-link :to="{ path: '#' }" class="flex text-center text-sm p-3 hover:text-secondary transition-all duration-150"  v-for="(nav, index) in navs" :key="index">
          {{ nav.name }}
        </router-link>
        <div class="flex items-center justify-center gap-3"
        </div>
      </div>
      <div class="ml-auto flex gap-5 items-center">
        <div class="action-btns hidden md:flex gap-2 items-center" v-if="!my.token">
          <LoginButton/>
          <Button type="solid" label="Register" color="primary" />
        </div>
    
        <router-link v-else :to="{ name: roleHome[my.role] }">
          <UserCircle />
        </router-link>
        <Button type="outline" icon="Menu" class="flex md:hidden py-3 hover:bg-primary hover:border-primary" @click="toggleSideMenu" />
      </div>

    </div>
  </header>
</template>

<script setup>
import images from '@/assets/images'
import { reactive, ref } from 'vue'
import BannerComponent from '@/components/public/BannerComponent.vue';
import Button from '@/components/buttons/Button.vue';
import { useUserStore } from '@/stores/auth';
import LoginButton from '@/components/buttons/LoginButton.vue';

const my = useUserStore()

const roleHome = {
  admin: 'Admin',
  librarian: 'Librarian',
  patron: 'Patron',
}

const sideMenu = reactive({
  show: false,
})

function toggleSideMenu() {
  sideMenu.show = !sideMenu.show
}

const navs = ref([
  {
    name: 'Home',
    link: '',
  },
  {
    name: 'OPAC',
    link: '',
  },
  {
    name: 'AcaRepo',
    link: '',
  },
  {
    name: 'About',
    link: '',
  },
  {
    name: 'Contact Us',
    link: '',
  },
])
</script>

<style>
.navs a {
  outline: none;
}

.navs a:focus-within {
  color: var(--color-primary)  ;
}
</style>