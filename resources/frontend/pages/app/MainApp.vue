<template>
  <div class="flex flex-col w-full p-5 gap-6 mx-auto">
    <Hero />

    <div class="flex flex-col gap-5 max-w-6xl mx-auto">
      <Card class="p-0" v-for="r in routes.filter(isVisible)">
        <template #header>
          <h2 class="text-xl font-bold text-primary">{{ r.title }}</h2>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          <Card class="cursor-pointer hover:shadow-primary-light/50 hover:shadow-lg rounded-lg transition duration-200" @click="auth.action" v-for="auth in r.children.filter(isVisible)">
            <component :is="auth.icon" class="h-10 w-10 my-3 text-primary" />
            <h2 class="text-xl font-bold">{{ auth.title }}</h2>
            <p class="mt-2 text-muted">{{ auth.description }}</p>
          </Card>
        </div>
      </Card>

      <!-- Online Subscriptions -->
      <Card class="p-0">
        <template #header>
          <h2 class="text-xl font-bold text-primary">Online Subscriptions</h2>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
          <Card class="cursor-pointer hover:shadow-primary-light/50 hover:shadow-lg rounded-lg transition duration-200" @click="preview(sub)" v-for="sub in onlineSubscriptions">
            <div class="flex p-4 mb-5 h-40 overflow-hidden border-b border-gray-300">
              <img :src="onlineResources[sub.img] || images.isu" alt="Logo" class="h-full w-auto m-auto" />
            </div>
            <h2 class="text-xl font-bold">{{ sub.name }}</h2>
            <p class="mt-2 text-gray-500">{{ sub.description }}</p>
          </Card>
        </div>
      </Card>
    </div>
  </div>

  <!-- Modals -->
  <ModalLayout ref="test">
    <div class="flex flex-col items-center gap-4">
      <div class="flex flex-col items-center gap-6">
        <img :src="images.construction" alt="" class="h-50 w-50 rounded-lg" />
        <h1 class="font-semibold text-2xl">Page is not yet ready.</h1>
      </div>
      <p class="text-center text-gray-600 font-light">Wait, tapusing lang namin 'yung page. Sorry ah, 'wag mag-madali. Balik ka nalang, salamat!</p>
      <!-- <p class="text-center">Unfornately, this feature is still under construction or testing, please wait until it's finished before accessing again. Thank you for understanding.</p> -->
      <Button label="Close" color="red" @click="test?.close()" />
    </div>
  </ModalLayout>

  <ModalLayout ref="eResCred">
    <template #header>
      <h1 class="text-xl">Access Credential for {{ selectedERes.name }}</h1>
    </template>

    <div class="flex flex-col gap-5">
      <div class="flex items-center text-sm gap-2 text-blue-500">
        <AlertCircle class="h-4 w-4" />
        <h1>You can login to the website using this credentials.</h1>
      </div>
      <div class="grid grid-cols-2 border-b border-gray-300 p-2">
        <span>Username</span>
        <span class="text-gray-500">{{ selectedERes?.username || 'Null' }}</span>
      </div>
      <div class="grid grid-cols-2 border-b border-gray-300 p-2">
        <span>Password</span>
        <span class="text-gray-500">{{ selectedERes?.password || 'Null' }}</span>
      </div>
    </div>

    <template #footer>
      <div class="flex justify-end">
        <Button @click="visit(selectedERes.link)" label="Visit" color="blue" />
      </div>
    </template>
  </ModalLayout>

  <LoginModal ref="login" />
</template>

<script setup>
import images from '@/assets/images'
import Button from '@/components/buttons/Button.vue'
import Card from '@/components/Card.vue'
import LoginModal from '@/components/modals/LoginModal.vue'
import ModalLayout from '@/layouts/ModalLayout.vue'
import router from '@/router'
import onlineResources from '@/assets/images/onlineResources/index'
import onlineSubscriptions from '@/services/onlineSubscriptions'
import { authStore } from '@/stores/auth'
import { ref } from 'vue'
import Hero from '@/sections/landing/Hero.vue'
import { ChevronDown } from 'lucide-vue-next'

const login = ref()
const test = ref()
const eResCred = ref()
const user = authStore()
const selectedERes = ref(null)
const openAnnouncements = ref(false)

const routes = ref([
  {
    title: 'Access',
    children: [
      { title: 'Login', description: 'Login and enjoy services exclusive for ISU.', icon: 'CircleUserRound', action: () => login.value?.open(), visibility: 'guest' },
      { title: 'Register', description: 'Not from ISU? We got you! Create an account as a guest and enjoy limited services from ISU.', icon: 'UserRoundPlus', action: () => test.value?.open(), visibility: 'guest' },
      { title: 'Dashboard', description: 'Click this to view your homepage.', icon: 'LayoutDashboard', action: () => user.home(), visibility: 'auth' },
    ],
  },
  {
    title: 'Services',
    children: [
      { title: 'OPAC', description: 'The Online Public Access Catalog of the Isabela State University.', icon: 'LaptopMinimal', action: () => open('OPAC') },
      { title: 'AcaRepo', description: 'The digital repository for academic materials of the Isabela State University.', icon: 'BookMarked', action: () => test.value?.open() },
      { title: 'Thesaurus', description: 'A wide collection of knowledge.', icon: 'BookType', action: () => test.value?.open() },
    ],
  },
])

const announcements = ref([
  {
    title: 'University Library - Santiago Campus launching soon.',
    description: 'The Isabela State University Santiago Campus, who recently officially declared as an official campus  from extension campus is conducting a Library Launch Event for everyone this upcoming July 15, 2026. Anyone is allowed to participate.',
    by: 'Isabela State University',
    date: 'June 15, 2026 - 10:01 PM',
  },
  {
    title: 'University Library - Santiago Campus launching soon.',
    description: 'The Isabela State University Santiago Campus, who recently officially declared as an official campus  from extension campus is conducting a Library Launch Event for everyone this upcoming July 15, 2026. Anyone is allowed to participate.',
    by: 'Isabela State University',
    date: 'June 15, 2026 - 10:01 PM',
  },
  {
    title: 'University Library - Santiago Campus launching soon.',
    description: 'The Isabela State University Santiago Campus, who recently officially declared as an official campus  from extension campus is conducting a Library Launch Event for everyone this upcoming July 15, 2026. Anyone is allowed to participate.',
    by: 'Isabela State University',
    date: 'June 15, 2026 - 10:01 PM',
  },
])

const isVisible = (item) => {
  switch (item.visibility) {
    case 'guest':
      return !user.isAuthenticated
    case 'auth':
      return user.isAuthenticated
    default:
      return true
  }
}

function open(route) {
  router.push({ name: route })
}

function preview(res) {
  if (user.isAuthenticated) {
    selectedERes.value = res
    eResCred.value?.open()
  } else {
    alert('You need to be logged in to proceed.')
  }
}

function visit(link) {
  window.open(link, '_blank')
}
</script>

<style scoped></style>
