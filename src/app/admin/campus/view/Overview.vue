<template>
  <div class="size-full flex flex-col gap-5 w-full max-w-7xl mx-auto">
    <SectionHeader :title="campus.currentData?.name as string" :description="`Manage ${campus.currentData?.name}'s Information`">
      <div class="flex items-start justify-end gap-2">
        <UpdateCampusModal :data="campus.currentData as Campus" />
        <Button class="text-danger!" left-icon="trash" @click="remove">Delete</Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col gap-5 p-5">
      <Card>
        <CardBody class="grid grid-cols-2 gap-5">
          <h1 class="col-span-2 uppercase font-semibold text-muted-foreground">Campus Information</h1>
          <div>
            <p class="card-label">Name</p>
            <p class="card-data">{{ campus.currentData?.name }}</p>
          </div>
          <div>
            <p class="card-label">Code</p>
            <p class="card-data">{{ campus.currentData?.code }}</p>
          </div>
          <div>
            <p class="card-label">Status</p>
            <Status :variant="parse.status(campus.currentData?.status as string)" class="card-data">{{ campus.currentData?.status }}</Status>
          </div>
          <div>
            <p class="card-label">Address</p>
            <p class="card-data">{{ campus.currentData?.address }}</p>
          </div>
        </CardBody>
      </Card>
      <div class="grid grid-cols-2 gap-5">
        <Card>
          <CardBody class="flex items-center gap-5">
            <span class="flex border border-success/15 rounded-xl text-success bg-success-soft size-13"><Icon class="m-auto text-xl" icon="calendar-plus" /></span>
            <div class="space-y-1.5">
              <p class="card-label">Date Created</p>
              <p class="card-data">{{ parse.formatDate(campus.currentData?.created_at) }}</p>
            </div>
          </CardBody>
        </Card>
        <Card>
          <CardBody class="flex items-center gap-5">
            <span class="flex border border-restore/15 rounded-xl text-restore bg-restore-soft size-13"><Icon class="m-auto text-xl" icon="clock-history" /></span>
            <div class="space-y-1.5">
              <p class="card-label">Last Modified</p>
              <p class="card-data">{{ parse.formatDateAgo(campus.currentData?.updated_at) }}</p>
            </div>
          </CardBody>
        </Card>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import UpdateCampusModal from '@/app/admin/campus/modals/UpdateCampusModal.vue'

const pop = usePopup()
const campus = useCampusStore()
const parse = useParser()

async function remove() {
  const res = await pop.confirm({ text: `Are you sure you want to delete ${campus.currentData?.name}?` })

  if (res.isConfirmed) {
    pop.load()
    try {
      const res = await campus.remove(campus.currentData as Campus)
      pop.success(res.message ?? 'Something is deleted')
      router.replace({ name: 'admin.campus' })
    } catch (e) {
      throw e
    }
  }
}
</script>

<style scoped>
.card-label {
  text-transform: uppercase;
  letter-spacing: 0.025rem;
  color: var(--color-muted-foreground);
  font-size: 0.75rem;
  font-weight: 500;
  margin-bottom: 0.25rem;
}

.card-data {
  font-weight: 500;
  /* text-transform: capitalize; */
}
</style>
