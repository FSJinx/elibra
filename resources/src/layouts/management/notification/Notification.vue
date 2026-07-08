<template>
  <div class="relative flex flex-col items-end bottom-0 right-0" ref="notification">
    <NotificationTriggerButton @click="show = !show" :active="show" :hasNotification="hasNotification" />
    <NotificationBody :show="show" @hasNotifications="setHasNotification" />
    <RedMark v-if="hasNotification" class="absolute -top-1 -right-1" />
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import NotificationTriggerButton from './NotificationTriggerButton.vue'
import NotificationBody from './NotificationBody.vue'
import RedMark from '@/components/ui/RedMark.vue'
import { useClickOutside } from '../../../composables/useClickOutside.js'

const show = ref(false)
const hasNotification = ref(false)
const notification = ref<HTMLElement | null>(null)

function setHasNotification(value: boolean) {
  hasNotification.value = value
}

useClickOutside(notification, () => {
  show.value = false
})
</script>

<style scoped></style>
