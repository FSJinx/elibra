<template>
  <aside class="flex flex-col min-h-screen max-w-75 w-75 p-5 border-r border-gray-200 bg-white">
    <!-- Logo -->
    <div class="logo flex items-center gap-3 mb-5">
      <img :src="images.logo" alt="" class="h-10" />
      <h1 class="text-primary font-extrabold text-2xl">e-Libra</h1>
    </div>

    <div class="person flex items-center gap-3 mb-5">
      <div class="flex items-center justify-center pfp rounded-4xl border border-primary w-13 h-13">
        <!-- <img src="" alt="" /> -->
        <UserCircle class="h-full w-full" :stroke-width="1" />
      </div>
      <div class="intro flex flex-col">
        <p>Welcome!</p>
        <p class="font-bold">Juan Dela Cruz</p>
      </div>
    </div>

    <div class="flex flex-col gap-1 mb-8" v-for="(menu, index) in menus" :key="index">
      <div class="flex text-neutral-500 text-xs font-bold mb-2">{{ menu.name }}</div>
      <div class="flex flex-col">
        <router-link :class="$route.name === child.path ? 'bg-secondary/10 border-l-3 text-primary' : ''" class="flex gap-3 py-2 px-3 hover:bg-secondary/10 hover:border-l-3 hover:text-primary border-primary transition-all duration-50 overflow-hidden" :to="{ name: child.path }" v-for="(child, index) in menu.child" :key="menu.name + index">
          <component :is="child.icon"></component>
          <span>{{ child.name }}</span>
        </router-link>
      </div>
    </div>
  </aside>
</template>
<script setup>
import images from '@/assets/images'
import menus from './AdminSideBar'
import { UserCircle } from 'lucide-vue-next'
</script>
