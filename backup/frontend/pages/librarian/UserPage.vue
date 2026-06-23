<template>
  <Card class="flex-col">
    <NavigationSection @role="role = $event" />
    <Table :header="tableHeader" :data="data" />
  </Card>
</template>

<script setup lang="ts">
import Card from '@/components/Card.vue'
import Table from '@/components/Table.vue'
import NavigationSection from '@/sections/librarian/management/NavigationSection.vue'
import { computed, ref, watch } from 'vue'

const role = ref('')

const tableHeader = computed(() => {
  let header = []

  if (role.value === 'librarian') {
    header = [{ key: 'section', label: 'Section' }]
  } else if (role.value === 'patron') {
    header = [{ key: 'patron_type', label: 'Patron Type' }]
  } else {
    header = [{ key: 'role', label: 'Role' }]
  }

  return [{ key: 'name', label: 'Name', align: 'left' }, { key: 'username', label: 'ID Number' }, ...header, { key: 'status', label: 'Status' }]
})

const data = computed(() => {
  let d: any = [
    { role: 'librarian', name: 'Betsie M. Dela Cruz', username: 'betsie', section: 'Office', status: 'active' },
    { role: 'librarian', name: 'Angelo', username: 'angelo', section: 'Office', status: 'active' },
    { role: 'patron', name: 'Reignromar Chryzel Balico', username: '22-1513', patron_type: 'student', status: 'active' },
    { role: 'patron', name: 'Jef A. Mamaril', username: '22-0858', patron_type: 'student', status: 'active' },
    { role: 'patron', name: 'Eugene G. Tobias', username: '22-0112', patron_type: 'student', status: 'inactive' },
  ]

  return role.value ? d.filter((x: any) => x.role === role.value) : d
})
</script>

<style scoped></style>
