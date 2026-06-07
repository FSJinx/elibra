<template>
  <Card class="justify-between w-full mb-5">
    <nav class="flex items-center gap-1 px-4 py-2 bg-secondary/10 rounded-md">
      <span v-for="role in roles" :key="role.key" class="px-3 py-1 rounded-sm text-primary cursor-pointer transition-all duration-150" :class="[selectedClass(role.key)]" @click="selectRole(role)">
        {{ role.label }}
      </span>
    </nav>

    <div class="flex items-center gap-1">
      <form class="flex items-center gap-1">
        <BaseInput placeholder="Search here..." name="search" label=""/>
        <Button label="Search" color="primary" icon="search" type="submit" />
      </form>
      <Button label="Add New" icon="plus" />
    </div>
  </Card>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue'
import BaseInput from '@/components/BaseInput.vue'
import Button from '@/components/buttons/Button.vue'
import Card from '@/components/Card.vue'

const emit = defineEmits(['role'])

interface Role {
  key?: string
  label?: string
}

const selectedRole = ref<Role>({ key: '' })

const roles = [
  { key: '', label: 'All' },
  { key: 'librarian', label: 'Librarian' },
  { key: 'patron', label: 'Patron' },
]

const selectedClass = (role: string) => {
  if (selectedRole.value.key === role) {
    return 'bg-white shadow-xs border border-primary shadow-primary/20'
  } else {
    return 'border border-primary/0'
  }
}

const selectRole = (role: any) => {
  selectedRole.value = role
  emit('role', role.key)
}
</script>
