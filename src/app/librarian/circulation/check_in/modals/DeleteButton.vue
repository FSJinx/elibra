<template>
  <Button class="hover:text-danger" size="sm" @click="submit">Delete</Button>
</template>

<script setup lang="ts">
const props = defineProps<{ data: Circulation }>()

const circulation = circulationStore()
const pop = usePopup()

async function submit() {
  const confirm = await pop.confirm({ text: 'Confirm return of this item' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await circulation.remove(props.data)
      pop.success(res.message)
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>

<style scoped></style>
