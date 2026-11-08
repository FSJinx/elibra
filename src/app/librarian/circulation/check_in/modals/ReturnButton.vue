<!-- <template>
  <Button class="hover:text-restore" size="sm" @click="submit">Return</Button>
</template> -->
<template>
  <Button
    size="sm"
    :disabled="isReturned"
    :class="
      isReturned
        ? '!h-8 !px-2.5 !py-1 !border-0 !shadow-none !bg-green-50 !text-green-600 hover:!bg-green-50 disabled:!opacity-100 disabled:!cursor-default'
        : ''
    "
    @click="submit"
  >

    {{ isReturned ? 'Returned' : 'Return' }}
  </Button>
</template>

<script setup lang="ts">
const props = defineProps<{ data: Circulation }>()

const circulation = circulationStore()
const pop = usePopup()

const isReturned = computed(() => 
  props.data.status === 'returned' || !!props.data.returned_at
)

async function submit() {
  const confirm = await pop.confirm({ text: 'Confirm return of this item' })

  if (confirm.isConfirmed) {
    pop.load()
    try {
      const res = await circulation.checkIn(props.data)
      pop.success(res.message)
    } catch (e: any) {
      pop.error(e.response?.data?.message)
    }
  }
}
</script>

<style scoped></style>
