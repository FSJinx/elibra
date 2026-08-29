<template>
  <div class="relative" ref="managementProfile">
    <Button @click="toggle">
      <!-- <span class="grid place-content-center rounded-full mr-2">
        <img :src="user.user?.profile_picture" alt="" class="size-9" v-if="user.user?.profile_picture" />
        <span v-else class="size-full font-semibold flex items-center justify-center text-primary text-xs border p-1 rounded-full">{{ user.getInitials }}</span>
      </span> -->
      <span class="shrink-0 capitalize">{{ user.user?.first_name }}</span>
    </Button>

    <Transition name="pop-in">
      <div class="absolute flex flex-col right-0 rounded-lg w-full min-w-100 max-h-150 border border-border drop-shadow mt-2 p-0 bg-background z-50" v-if="open">
        <div class="flex items-center gap-3 p-5 rounded-md pb-0">
          <!-- Profile Circle -->
          <!-- <span class="flex items-center justify-center aspect-square size-15 bg-success-soft rounded-full text-success-soft-foreground">
            <Icon icon="person" class="text-2xl" />
          </span> -->
          <span class="size-15 font-semibold flex items-center justify-center text-primary text-lg border rounded-full">{{ user.getInitials }}</span>

          <div class="flex flex-col">
            <!-- Profile Name -->
            <div class="flex items-center justify-between gap-1">
              <h1 class="capitalize font-semibold line-clamp-1">{{ user.getFullName }}</h1>
              <Badge class="capitalize" :variant="parse.status(user.user?.role || 'patron')">{{ user.user?.role }}</Badge>
            </div>
            <!-- Profile Username -->
            <span class="text-sm text-foreground-secondary">@{{ user.user?.username }}</span>
          </div>
        </div>
        <!-- Profile Dropdown Menu -->
        <div class="flex-1 flex flex-col gap-2 overflow-y-auto scrollbar-thin p-5">
          <h2 class="font-semibold uppercase tracking-wide text-foreground-secondary text-sm">Options</h2>
          <div class="flex flex-col">
            <Button variant="text" align="left" left-icon="person"> Profile</Button>
            <Button variant="text" align="left" left-icon="door-open" class="text-danger-soft-foreground hover:bg-danger-soft" @click="auth.logout">Logout</Button>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup lang="ts">
const user = authStore()
const auth = useAuth()
const open = ref<boolean>(false)
const parse = useParser()
const managementProfile = ref<HTMLElement | null>(null)

const toggle = () => {
  open.value = !open.value
}

const close = () => {
  open.value = false
}

useClickOutside(managementProfile, close)
</script>

<style scoped></style>
