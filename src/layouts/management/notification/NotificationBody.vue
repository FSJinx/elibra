<template>
  <Transition name="pop-in">
    <div class="absolute top-11 -right-10 bg-white shadow-lg w-sm rounded-xl border border-slate-200 max-h-150 flex flex-col overflow-hidden z-50" v-show="show">
      <!-- Notification Header -->
      <div class="flex items-center justify-between p-5 shrink-0">
        <div class="flex items-center gap-3">
          <span class="p-2 border border-green-300 rounded bg-green-50">
            <Bell class="h-4 w-4" />
          </span>
          <h1 class="text-xl font-semibold leading-none">Notifications</h1>
        </div>
        <router-link to="" class="text-sm text-secondary hover:underline transition duration-200 z-50">See all</router-link>
      </div>
      <!-- Notification Body -->
      <div class="min-h-0 flex-1 overflow-y-auto">
        <div class="flex flex-col">
          <template v-if="!notifications.length">
            <span class="p-5 pb-10 text-center text-slate-500">No notifications.</span>
          </template>
          <template v-for="(notif, index) in notifications" :key="index" v-else>
            <div class="notification-tile relative flex items-start hover:bg-slate-100 p-5 py-3 cursor-pointer" :class="{ 'text-black': !notif.isRead }" :title="notif.isRead ? notif.title : 'Click notification to view details.'">
              <div class="flex flex-col flex-1 text-sm">
                <h5 class="font-semibold" :class="[notif.isRead ? 'text-gray-500' : 'text-black']">{{ notif.title }}</h5>
                <p class="text-gray-500 font-light text-sm line-clamp-1">{{ notif.message }}</p>
              </div>
              <div class="read-marker flex mr-2" :class="[notif.isRead ? 'text-slate-500 font-normal' : 'text-black font-semibold']">
                <p class="text-xs">{{ notif.time }}</p>
              </div>
              <RedMark :show="!notif?.isRead" />
            </div>
            <span class="p-3 border-t border-slate-200 text-sm text-center text-slate-400 font-normal" v-if="notifications.length - 1 === index">End of list.</span>
          </template>
        </div>
      </div>
    </div>
  </Transition>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'

interface Props {
  show?: boolean
}

const emit = defineEmits(['hasNotifications'])
withDefaults(defineProps<Props>(), {
  show: false,
})

const notifications = ref([
  { title: 'New Book Added', message: 'A new book has been added to the library. A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'New Acquisition', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'An item is requested for reservation.', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: true },
  { title: 'New Book Added', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'New Acquisition', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'An item is requested for reservation.', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: true },
  { title: 'An item is requested for reservation.', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: true },
  { title: 'New Book Added', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'New Acquisition', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'New Book Added', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'New Acquisition', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: false },
  { title: 'An item is requested for reservation.', message: 'A new book has been added to the library.', time: '2 hours ago', isRead: true },
])

const hasNotifications = computed(() => notifications.value.some((notif) => !notif.isRead))

watch(
  hasNotifications,
  (value) => {
    emit('hasNotifications', value)
  },
  { immediate: true },
)
</script>

<style scoped></style>
