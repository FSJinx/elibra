<template>
  <div class="size-full flex flex-col overflow-hidden">
    <div class="bg-background border-b border-border p-5">
      <Button icon="arrow-left" variant="text" @click="router.back()"></Button>
      <span class="truncate">{{ users.currentData?.first_name }}'s Profile</span>
    </div>

    <div class="size-full grid grid-cols-1 lg:grid-cols-[320px_minmax(0,1fr)] gap-4 p-4 lg:p-5 overflow-hidden">
      <Card class="min-h-0">
        <CardBody class="flex flex-col gap-4 overflow-hidden">
          <div class="size-30 border-4 border-background shadow-lg rounded-full mt-2 mx-auto contain-content overflow-hidden ring-1 ring-border">
            <img :src="profileImage" :alt="users.currentData?.name?.full" class="w-full h-full object-cover" />
          </div>
          <div class="text-center space-y-1">
            <Title :level="3" class="truncate line-clamp-1">{{ users.currentData?.name?.full }}</Title>
            <p class="text-sm text-muted-foreground">
              <span class="capitalize">{{ users.currentData?.role_text ?? 'User' }}</span> - {{ users.currentData?.username }}
            </p>
          </div>

          <div class="flex-1 flex flex-col gap-1 overflow-y-auto pt-2 border-t border-border">
            <router-link v-for="item in routes" :key="item.route" class="px-4 py-3 rounded-lg text-sm text-muted-foreground transition duration-150 hover:bg-default/25 hover:text-foreground" exact-active-class="bg-primary-soft/30! border border-primary/20 text-primary! font-medium" :to="{ name: item.route, params: { id: route.params.id } }">
              {{ item.label }}
            </router-link>
          </div>
        </CardBody>
      </Card>

      <div class="flex-1 overflow-hidden">
        <Alert class="mb-4" variant="info" v-if="users.currentData?.registration === 'pending'" no-icon>
          <div class="flex items-center gap-2">
            <p class="mr-auto">This account is still pending for approval, review now.</p>

            <Button variant="danger" @click="reviewRegistration('reject')">Reject</Button>
            <Button variant="success" @click="reviewRegistration('approve')">Approve</Button>
          </div>
        </Alert>

        <router-view />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import default_image from '@/assets/images/user.png'
import { storageRoute } from '@/plugins/axios'

const route = useRoute()
const router = useRouter()
const users = userStore()
const pop = usePopup()

const profileImage = computed(() => {
  const picture = users.currentData?.profile_picture ?? users.currentData?.profilePicture
  const path = typeof picture === 'string' ? picture : picture?.file_path
  if (!path) return default_image
  if (/^https?:\/\//i.test(path)) return path
  return `${storageRoute}/${path.replace(/^\/+/, '').replace(/^storage\//, '')}`
})

const routes = ref([
  { label: 'Profile', route: 'admin.users.view' },
  { label: 'Account', route: 'admin.users.account' },
  { label: 'Circulation', route: 'admin.users.view.circulation' },
  { label: 'Reservation', route: 'admin.users.view.reservation' },
  { label: 'Attendance', route: 'admin.users.view.attendance' },
])

async function load() {
  await users.show(String(route.params.uuid) as User['uuid'])
}

async function reviewRegistration(action: 'approve' | 'reject') {
  const actionLabel = action === 'approve' ? 'approve' : 'reject'
  const confirmation = await pop.confirm({ text: `Are you sure you want to ${actionLabel} ${users.currentData?.first_name} ${users.currentData?.last_name}'s registration?` })

  if (confirmation.isConfirmed) {
    pop.load()
    try {
      const result = action === 'approve' ? await users.approveRegistration(users.currentData as User) : await users.rejectRegistration(users.currentData as User)

      pop.success(result.message)
      load()
    } catch (error: any) {
      await pop.error(error?.response?.data?.message)
    }
  }
}

onBeforeUnmount(() => (users.currentData = null))
</script>

<style scoped></style>
