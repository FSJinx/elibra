<template>
  <div class="flex items-center w-full py-3 px-4 bg-background border-b border-border">
    <!-- Right Side -->
    <span class="flex cursor-pointer size-11 group" :data-title="system.sidebar ? 'Close Sidebar' : 'Open Sidebar'" @click="system.toggleSidebar">
      <img :src="isu" alt="" class="block size-11 group-hover:hidden" />
      <Icon :icon="system.sidebar ? 'x-lg' : 'list'" class="hidden group-hover:block m-auto text-lg transition-all duration-300" />
    </span>
    <div class="inline-flex flex-col ml-3">
      <h5 class="font-semibold text-lg leading-5">{{ heading }}</h5>
      <p class="text-sm font-normal">{{ subHeading }}</p>
    </div>

    <!-- Left Buttons -->
    <div class="flex items-center justify-end gap-1.5 ml-auto">
      <ManagementSearchButton />
      <Button icon="bell"></Button>
      <ManagementProfileButton />
    </div>
  </div>
</template>

<script setup lang="ts">
import ManagementProfileButton from '@/layouts/management/ManagementProfileButton.vue'
import ManagementSearchButton from '@/layouts/management/ManagementSearchButton.vue'
import isu from '@/assets/images/isu.png'

const auth = authStore()
const system = systemStore()
const parse = useParser()

const subHeading = computed(() => {
  const branch = ref<string>('')
  if (auth.user?.role === 'librarian') {
    branch.value = auth.user?.branch.name
  }
  return `${branch.value ?? 'Unknown'}, ${parse.toCapital(auth.user?.role || 'Unknown')}`
})

const heading = computed(() => {
  const campus = ref<string>('')

  if (auth.user?.role === 'librarian' || auth.user?.role === 'admin') {
    campus.value = ' - ' + auth.user?.campus.name
  } else if (auth.user?.role === 'super admin') {
    campus.value = ' - Global'
  }

  return 'Isabela State University' + campus.value
})
</script>

<style scoped></style>
