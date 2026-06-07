<template>
  <header class="fixed inset-x-0 top-0 flex justify-between w-screen z-10 transition-shadow duration-500 overflow-hidden" :class="{ 'shadow-md': scrollY > 10 }" :style="headerStyle">
    <div class="flex items-center h-full gap-3">
      <img :src="images.isu" alt="" class="h-11" />
      <div class="flex flex-col justify-between overflow-hidden whitespace-nowrap">
        <h1 class="text-2xl font-extrabold text-primary leading-6.5">e-Libra</h1>
        <p class="text-sm hidden sm:inline">The ISU-One Library Management System of the <span class="font-bold text-primary">ISABELA STATE UNIVERSITY</span></p>
        <p class="text-sm inline sm:hidden">The LMS of <span class="font-bold text-primary">ISABELA STATE UNIVERSITY</span></p>
      </div>
    </div>
    <div class="flex items-center justify-end p-4 gap-2">
      <LoginButton />
      <Button variant="solid" color="primary" label="Register" />
    </div>
  </header>
</template>

<script setup>
import images from '@/assets/images'
import AnchorButton from '@/components/buttons/AnchorButton.vue'
import Button from '@/components/buttons/Button.vue'
import LoginButton from '@/components/buttons/LoginButton.vue'
import { computed, onMounted, onUnmounted, ref } from 'vue'

const scrollY = ref(0)
const windowWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 0)

const headerStyle = computed(() => {
  const scrollRange = 200

  const maxHeight = 140 // h-35
  const minHeight = 80 // h-20
  const currentHeight = Math.max(minHeight, maxHeight - (scrollY.value * (maxHeight - minHeight)) / scrollRange)

  const currentOpacity = Math.min(1, scrollY.value / scrollRange)

  let currentPadding = 0

  if (windowWidth.value >= 768) {
    const maxPadding = 320
    const minPadding = 20

    currentPadding = Math.max(minPadding, maxPadding - (scrollY.value * (maxPadding - minPadding)) / scrollRange)
  }

  return {
    height: `${currentHeight}px`,
    backgroundColor: `rgba(255, 255, 255, ${currentOpacity})`,
    paddingLeft: `${currentPadding}px`,
    paddingRight: `${currentPadding}px`,
  }
})

const handleScroll = () => {
  scrollY.value = window.scrollY
}

const handleResize = () => {
  windowWidth.value = window.innerWidth
}

onMounted(() => {
  window.addEventListener('scroll', handleScroll, { passive: true })
  window.addEventListener('resize', handleResize)
})

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll)
  window.removeEventListener('resize', handleResize)
})
</script>
