<template>
  <div class="size-full flex flex-col overflow-hidden">
    <SectionHeader :title="`Currently Cataloging: ${acarepo.currentData?.title}`" description="Review submission's information before adding to catalog" icon=""></SectionHeader>

    <div class="flex-1 flex gap-5 p-5 overflow-y-auto">
      <Card class="flex-1">
        <CardHeader>Submission Preview</CardHeader>
        <CardBody class="flex flex-col gap-3 overflow-y-auto -m-5 p-5">
          <Control id="" col>
            <Label>Title</Label>
            <Input type="text" placeholder="" :model-value="acarepo.currentData?.title" readonly />
          </Control>
          <Control id="" col>
            <Label>Category</Label>
            <Input type="text" placeholder="" :model-value="acarepo.currentData?.category === 'ut' ? 'Undergraduate' : 'Graduate'" readonly />
          </Control>
          <Control id="" col>
            <Label>Abstract</Label>
            <Textarea placeholder="" :model-value="acarepo.currentData?.description" readonly />
          </Control>
          <Control id="" col>
            <Label>Digital Object Identifier (DOI)</Label>
        <Textarea placeholder="" :model-value="acarepo.currentData?.doi" readonly />
          </Control>
          <Control id="" col>
            <Label>Electronic File (.pdf)</Label>
            <InputFile :model-value="acarepo.currentData?.electronic_file"/>
          </Control>
        </CardBody>
      </Card>
      <div class="flex-1"></div>
    </div>
  </div>
</template>

<script setup lang="ts">
const acarepo = acaRepoStore()
</script>

<style scoped></style>
