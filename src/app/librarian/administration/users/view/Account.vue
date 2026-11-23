<template>
  <div v-if="users.currentData" class="flex flex-col gap-5 overflow-y-auto">
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-5">
      <Card>
        <CardHeader>
          <div>
            <p class="font-semibold">Account credentials</p>
            <p class="text-xs text-muted-foreground mt-1">Identity and authentication details</p>
          </div>
        </CardHeader>
        <CardBody class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <Dl
            ><Dt>Username</Dt><Dd class="font-medium">{{ users.currentData.username || '—' }}</Dd></Dl
          >
          <Dl
            ><Dt>Email address</Dt><Dd class="break-all">{{ users.currentData.email || '—' }}</Dd></Dl
          >
          <Dl
            ><Dt>Role</Dt
            ><Dd
              ><Status :variant="parse.status(users.currentData.role)">{{ users.currentData.role_text || users.currentData.role || '—' }}</Status></Dd
            ></Dl
          >
          <Dl
            ><Dt>UUID</Dt><Dd class="break-all text-xs">{{ users.currentData.uuid || '—' }}</Dd></Dl
          >
        </CardBody>
      </Card>

      <Card>
        <CardHeader>
          <div>
            <p class="font-semibold">Account status</p>
            <p class="text-xs text-muted-foreground mt-1">Access and registration state</p>
          </div>
        </CardHeader>
        <CardBody class="grid grid-cols-1 sm:grid-cols-2 gap-5">
          <Dl
            ><Dt>System status</Dt
            ><Dd
              ><Status :variant="parse.status(users.currentData.status)">{{ users.currentData.status || 'inactive' }}</Status></Dd
            ></Dl
          >
          <Dl
            ><Dt>Registration</Dt
            ><Dd
              ><Status :variant="parse.status(users.currentData.registration ?? 'not reviewed')">{{ users.currentData.registration || 'not reviewed' }}</Status></Dd
            ></Dl
          >
          <Dl
            ><Dt>Login attempts</Dt><Dd>{{ users.currentData.login_attempts ?? 0 }}</Dd></Dl
          >
          <Dl
            ><Dt>Email verified</Dt><Dd>{{ users.currentData.email_verified_at ? parse.formatDateTime(users.currentData.email_verified_at) : 'Not verified' }}</Dd></Dl
          >
        </CardBody>
      </Card>
    </div>

    <div class="flex items-center justify-between p-3 text-xs">
      <span>
        Account Created <span class="font-semibold">{{ parse.formatDateTime(users.currentData?.created_at) }}</span>
      </span>
      <span>
        Last Modified <span class="font-semibold">{{ parse.formatDateTime(users.currentData?.updated_at) }}</span>
      </span>
    </div>
  </div>
  <div v-else class="flex size-full items-center justify-center p-5 text-muted-foreground">Loading account information...</div>
</template>

<script setup lang="ts">
const users = userStore()
const parse = useParser()
const pop = usePopup()
const reviewing = ref(false)

async function review(action: 'approve' | 'reject') {
  const user = users.currentData
  if (!user || user.registration !== 'pending') return

  const confirmed = await pop.confirm({
    text: `Are you sure you want to ${action} ${user.name?.full || `${user.first_name} ${user.last_name}`}?`,
  })
  if (!confirmed.isConfirmed) return

  reviewing.value = true
  pop.load()
  try {
    const result = action === 'approve' ? await users.approveRegistration(user) : await users.rejectRegistration(user)
    await users.show(String(user.id))
    await pop.success(result.message || `Registration ${action === 'approve' ? 'approved' : 'rejected'} successfully.`)
  } catch (error: any) {
    await pop.error(error?.response?.data?.message || `Unable to ${action} this registration.`)
  } finally {
    reviewing.value = false
  }
}
</script>
