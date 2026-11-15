<template>
  <div class="size-full flex justify-center gap-3 mx-auto w-full p-5 overflow-hidden">
    <OpacFilter v-model:params="params" @apply="fetch" @reset="reset" />
    <OpacMain v-model:params="params" :data="pagination" :loading="loading" :error="error" @search="fetch" />
  </div>
  <!-- <OpacHistory /> -->
</template>

<script setup lang="ts">
import OpacFilter from '@/app/opac/pages/main/OpacFilter.vue'
import OpacMain from '@/app/opac/pages/main/OpacMain.vue'

const route = useRoute()
const opac = opacStore()

const defaultParams = (): Partial<OpacParams> => ({
  query: (route.query.query as string) ?? '',
})

const params = ref<Partial<OpacParams>>(defaultParams())
const loading = ref<boolean>(false)
const pagination = ref<Pagination>()
const error = ref()

async function search() {
  loading.value = true
  try {
    const res = await opac.fetch(params.value)
    if (res.status === 'error') return (error.value = res.message)

    pagination.value = res.data
  } catch (e: any) {
    error.value = e.response?.data?.message
    throw e
  } finally {
    loading.value = false
  }
}

function fetch() {
  router.push({ name: 'opac', query: params.value })
  search()
}

function reset() {
  // Object.assign(params.value, defaultParams())

  router.push({ name: 'opac' })
}

onMounted(search)
watch(
  () => params.value.query,
  () => {
    if (!params.value.query) fetch()
  },
  { immediate: true, deep: true },
)
</script>

<style scoped></style>
