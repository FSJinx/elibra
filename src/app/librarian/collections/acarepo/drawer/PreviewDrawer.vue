<template>
  <Button @click="open" class="mr-auto">View: {{ data?.title }}</Button>

  <Drawer ref="drawer">
    <DrawerHeader>Preview: {{ data?.title }}</DrawerHeader>
    <DrawerBody class="flex flex-col gap-4">
      <Control col>
        <Label>Title</Label>
        <Input type="text" placeholder="" :model-value="data?.title" readonly />
      </Control>
      <Control col>
        <Label>Category</Label>
        <Input type="text" placeholder="" :model-value="data?.category === 'ut' ? 'Undergraduate' : 'Graduate'" readonly />
      </Control>
      <Control col>
        <Label>Abstract</Label>
        <Textarea placeholder="" :model-value="data?.description ?? '-'" readonly />
      </Control>
      <Control col>
        <Label>Digital Object Identifier (DOI)</Label>
        <Input type="text" placeholder="" :model-value="data?.doi ?? '-'" readonly />
      </Control>
      <Control col>
        <Label>Language</Label>
        <Input type="text" placeholder="" :model-value="data?.language?.name ?? '-'" readonly />
      </Control>
      <Control col>
          <Label>Keywords</Label>
          <div class="flex flex-wrap gap-2">
              <Chip v-for="item in data?.keywords">{{ item }}</Chip>
        </div>
      </Control>
    </DrawerBody>
  </Drawer>
</template>

<script setup lang="ts">
import Drawer from '@/components/drawer/Drawer.vue'

const props = defineProps<{ data: AcaRepo | null }>()

const drawer = ref<InstanceType<typeof Drawer>>()

function open() {
  drawer.value?.open()
}
</script>

<style scoped></style>
