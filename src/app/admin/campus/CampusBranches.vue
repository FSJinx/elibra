<template>
  <section class="p-5 space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <h2 class="text-lg font-semibold">Campus branches</h2>
        <p class="text-sm text-muted">Manage the library branches connected to this campus.</p>
      </div>
      <Button variant="primary" @click="startBranch()">Add Branch</Button>
    </div>

    <form v-if="editor" class="grid gap-3 rounded-lg border border-border bg-background p-4 md:grid-cols-[1fr_1fr_1fr_auto_auto]" @submit.prevent="saveBranch">
      <Input id="branch-name" v-model="form.name" placeholder="Branch name" required />
      <Input id="branch-email" v-model="form.email" type="email" placeholder="Email" />
      <Input id="branch-contact" v-model="form.contact_info" placeholder="Contact information" />
      <Button type="submit" variant="primary" :loading="saving">{{ form.id ? 'Save' : 'Create' }}</Button>
      <Button type="button" variant="text" @click="cancelBranch">Cancel</Button>
    </form>

    <div v-if="loading" class="py-10 text-center"><Spinner /></div>
    <div v-else-if="!branches.length" class="rounded-lg border border-dashed border-border p-10 text-center text-muted">No branches have been added to this campus.</div>
    <div v-else class="overflow-x-auto rounded-lg border border-border">
      <table class="w-full text-sm">
        <thead class="border-b border-border bg-muted/20 text-left">
          <tr>
            <th class="p-3">Name</th>
            <th class="p-3">Contact</th>
            <th class="p-3">Email</th>
            <th class="p-3 text-right">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-border">
          <tr v-for="branch in branches" :key="branch.id">
            <td class="p-3 font-medium">{{ branch.name }}</td>
            <td class="p-3">{{ branch.contact_info || '—' }}</td>
            <td class="p-3">{{ branch.email || '—' }}</td>
            <td class="p-3 text-right">
              <Button variant="text" @click="startBranch(branch)">Edit</Button>
              <Button variant="danger" @click="removeBranch(branch)">Delete</Button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>

<script setup lang="ts">
interface Branch {
  id: number
  name: string
  email?: string
  contact_info?: string
}

const route = useRoute()
const branches = ref<Branch[]>([])
const loading = ref(false)
const saving = ref(false)
const editor = ref(false)
const form = reactive<{ id?: number; name: string; email: string; contact_info: string }>({ name: '', email: '', contact_info: '' })
const responseData = (res: any) => res.data?.data?.data ?? res.data?.data ?? []

async function fetchBranches() {
  loading.value = true
  try {
    const res = await api.get('branch/get', { params: { campus_id: route.params.id } })
    branches.value = responseData(res)
  } catch (error) {
    console.error('Failed to fetch branches:', error)
  } finally {
    loading.value = false
  }
}

function startBranch(branch?: Branch) {
  editor.value = true
  form.id = branch?.id
  form.name = branch?.name ?? ''
  form.email = branch?.email ?? ''
  form.contact_info = branch?.contact_info ?? ''
}

function cancelBranch() {
  editor.value = false
  delete form.id
  form.name = ''
  form.email = ''
  form.contact_info = ''
}

async function saveBranch() {
  saving.value = true
  try {
    const payload = { ...form, campus_id: Number(route.params.id) }
    if (form.id) await api.put(`branch/update/${form.id}`, payload)
    else await api.post('branch/create', payload)
    cancelBranch()
    await fetchBranches()
  } finally {
    saving.value = false
  }
}

async function removeBranch(branch: Branch) {
  if (!window.confirm(`Delete ${branch.name}?`)) return
  await api.delete(`branch/delete/${branch.id}`)
  await fetchBranches()
}

onMounted(fetchBranches)
</script>
