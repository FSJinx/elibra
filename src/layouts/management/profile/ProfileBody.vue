<template>
  <Transition name="pop-in">
    <div class="absolute top-11 right-0 bg-white drop-shadow-lg w-80 p-2 rounded-xl border border-slate-300 max-h-150 flex flex-col overflow-hidden z-50" v-if="show">
      <div class="flex flex-col justify-center text-center gap-2 p-5 pb-0">
        <div class="mx-auto p-3 border border-primary rounded-full overflow-hidden">
          <img :src="images.logo" alt="" class="h-15 w-15 mx" />
        </div>
        <div class="flex flex-col p-3 gap-2">
          <h1 class="capitalize font-semibold">{{ auth.fullName }}</h1>
          <div class="flex items-center justify-center text-sm text-slate-500">
            <span
              >{{ '@' + user?.username }} | <span class="capitalize">{{ user?.role }}</span></span
            >
          </div>
        </div>
      </div>

      <div class="min-h-0 flex-1 overflow-y-auto">
        <div class="flex flex-col">
          <template v-for="o in options">
            <div class="flex items-center p-5 py-4 gap-4 rounded-lg cursor-pointer hover:bg-gray-100" :class="[o.color]">
              <span class=""><component :is="o.icon" class="h-4 w-4" /></span>
              <span>{{ o.name }}</span>
            </div>
          </template>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import images from '../../../assets/images'
import { authStore } from '../../../stores/authStore'

interface Props {
  show: boolean
}

const props = withDefaults(defineProps<Props>(), {
  show: false,
})

const auth = authStore()
const user = computed(() => {
  return auth?.user
})

const options = ref([
  { name: 'Settings', icon: 'Settings' },
  { name: 'Logout', icon: 'LogOut', color: 'text-red' },
])
</script>

<style scoped></style>
