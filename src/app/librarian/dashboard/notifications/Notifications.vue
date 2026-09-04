<template>
  <div class="flex flex-col size-full overflow-hidden p-6 gap-6">
    <!-- Title Page -->
    <SectionHeader title="Notifications" description="This are all your notifications." class="p-0!">
      <div class="flex items-center justify-end gap-2">
        <Button>Mark all as read</Button>
      </div>
    </SectionHeader>

    <Table>
      <Tbody :data="data" :loading="false" :cols="3">
        <tr v-for="(item, index) in data" :class="[item.is_Read ? '' : 'bg-primary-soft']">
          <Td align="left">
            <p class="font-medium text-lg">{{ item.title }}</p>
            <p class="">{{ item.description }}</p>
          </Td>
          <Td align="right">
            <span>{{ item.date }}</span>
            <span class="size-2 bg-danger rounded-full ml-3"></span>
          </Td>
        </tr>
      </Tbody>
    </Table>
  </div>
</template>

<script setup lang="ts">
const data = [
  { title: 'Added an item', description: 'You added "Spider-man: Brand New Day to the catalog."', date: 'Aug 31, 2026', is_Read: false },
  { title: 'Added an item', description: 'You added "Spider-man: Brand New Day to the catalog."', date: 'Aug 31, 2026', is_Read: false },
  { title: 'Added an item', description: 'You added "Spider-man: Brand New Day to the catalog."', date: 'Aug 31, 2026', is_Read: false },
  { title: 'Added an item', description: 'You added "Spider-man: Brand New Day to the catalog."', date: 'Aug 31, 2026', is_Read: false },
]
</script>

<style scoped></style>
