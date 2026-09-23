<template>
  <div class="size-full flex flex-col gap-5">
    <SectionHeader :title="campus.currentData?.name as string" :description="`Manage ${campus.currentData?.name}'s Departments`">
      <div class="flex items-start justify-end gap-2 p-2">
        <Button variant="primary" left-icon="plus-lg">Add New</Button>
      </div>
    </SectionHeader>

    <div class="flex-1 flex flex-col p-5 gap-5">
      <Form>
        <Card>
          <Control>
            <Input id="" type="text" placeholder="Search by department name or dean..." class="max-w-125" />
          </Control>
        </Card>
      </Form>
      <Table title="Departments Table" :subtitle="`Manage departments under ${campus.currentData?.name}`"></Table>
    </div>
  </div>
</template>

<script setup lang="ts">
const campus = useCampusStore()
</script>

<style scoped></style>
