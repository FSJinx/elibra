<template>
  <div class="relative inline-flex" ref="profile">
    <ProfileTriggerButton @click="openProfile" :active="show"/>
    <ProfileBody :show="show" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import ProfileBody from './ProfileBody.vue'
import ProfileTriggerButton from './ProfileTriggerButton.vue'
import { useClickOutside } from '@/composables/useClickOutside.js'

const show = ref(false)
const profile = ref<HTMLElement | null>(null)

const openProfile = () => {
  show.value = !show.value
}

useClickOutside(profile, () => {
  show.value = false
})
</script>

<style scoped></style>
