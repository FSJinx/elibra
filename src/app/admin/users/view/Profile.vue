<template>
  <div v-if="users.currentData" class="flex flex-col gap-5 overflow-y-auto">
    <Card>
      <CardHeader>
        <div>
          <p class="font-semibold">Personal information</p>
          <p class="text-xs text-muted-foreground mt-1">Identity details registered for this account</p>
        </div>
      </CardHeader>
      <CardBody class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <Dl
          ><Dt>Full name</Dt><Dd class="font-medium">{{ users.currentData.name?.full }}</Dd></Dl
        >
        <Dl
          ><Dt>Email</Dt><Dd>{{ users.currentData.email || '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>Contact number</Dt><Dd>{{ users.currentData.contact_number || '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>Sex</Dt><Dd>{{ users.currentData.sex || '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>Birthdate</Dt><Dd>{{ users.currentData.birthdate ? parse.formatDate(users.currentData.birthdate) : '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>Campus</Dt><Dd>{{ users.currentData.campus?.name || '—' }}</Dd></Dl
        >
      </CardBody>
    </Card>

    <Card v-if="users.currentData.patron">
      <CardHeader>
        <div>
          <p class="font-semibold">Patron information</p>
          <p class="text-xs text-muted-foreground mt-1">Library membership details</p>
        </div>
      </CardHeader>
      <CardBody class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <Dl
          ><Dt>Patron type</Dt><Dd>{{ users.currentData.patron.patron_type?.name || '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>EBC number</Dt><Dd>{{ users.currentData.patron.ebc_number || '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>Program</Dt><Dd>{{ users.currentData.patron.program?.name || '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>Department</Dt><Dd>{{ users.currentData.department?.name || users.currentData.patron.program?.department?.name || '—' }}</Dd></Dl
        >
      </CardBody>
    </Card>

    <Card>
      <CardHeader>
        <p class="font-semibold">Account summary</p>
      </CardHeader>
      <CardBody class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <Dl
          ><Dt>Role</Dt><Dd>{{ users.currentData.role_text || users.currentData.role || '—' }}</Dd></Dl
        >
        <Dl
          ><Dt>Status</Dt
          ><Dd
            ><Status :variant="parse.status(users.currentData.status)">{{ users.currentData.status || '—' }}</Status></Dd
          ></Dl
        >
        <Dl
          ><Dt>Registration</Dt
          ><Dd
            ><Status :variant="parse.status(users.currentData.registration ?? 'not reviewed')">{{ users.currentData.registration ?? 'not reviewed' }}</Status></Dd
          ></Dl
        >
      </CardBody>
    </Card>
  </div>
  <div v-else class="flex size-full items-center justify-center p-5 text-muted-foreground">Loading user profile...</div>
</template>

<script setup lang="ts">
const users = userStore()
const parse = useParser()
</script>

<style scoped></style>
