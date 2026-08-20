<template>
  <section class="p-5 space-y-5">
    <div class="flex flex-wrap items-center justify-between gap-3">
      <div>
        <h2 class="text-lg font-semibold">Departments and programs</h2>
        <p class="text-sm text-muted">Organize academic departments and the programs they offer.</p>
      </div>
      <Button variant="primary" @click="startDepartment()">Add Department</Button>
    </div>

    <form v-if="departmentEditor" class="grid gap-3 rounded-lg border border-border bg-background p-4 md:grid-cols-[1fr_180px_auto_auto]" @submit.prevent="saveDepartment">
      <Input id="department-name" v-model="departmentForm.name" placeholder="Department name" required />
      <Input id="department-code" v-model="departmentForm.code" placeholder="Code" required />
      <Button type="submit" variant="primary" :loading="saving">{{ departmentForm.id ? 'Save' : 'Create' }}</Button>
      <Button type="button" variant="text" @click="cancelDepartment">Cancel</Button>
    </form>

    <div v-if="loading" class="py-10 text-center"><Spinner /></div>
    <div v-else-if="!departments.length" class="rounded-lg border border-dashed border-border p-10 text-center text-muted">No departments have been added to this campus.</div>
    <div v-else class="space-y-3">
      <article v-for="department in departments" :key="department.id" class="rounded-lg border border-border bg-background">
        <div class="flex flex-wrap items-center gap-3 p-4">
          <button class="flex min-w-0 flex-1 items-center gap-3 text-left" type="button" @click="togglePrograms(department)">
            <span class="font-semibold">{{ department.name }}</span>
            <span class="text-xs text-muted">{{ department.code }}</span>
            <span class="text-xs text-muted">{{ department.programs?.length ?? 0 }} programs</span>
          </button>
          <Button variant="text" @click="startDepartment(department)">Edit</Button>
          <Button variant="danger" @click="removeDepartment(department)">Delete</Button>
        </div>

        <div v-if="expanded[department.id]" class="border-t border-border bg-muted/20 p-4">
          <div class="mb-3 flex items-center justify-between gap-3">
            <h3 class="font-medium">Programs</h3>
            <Button @click="startProgram(department.id)">Add Program</Button>
          </div>

          <form v-if="programEditor === department.id" class="mb-3 grid gap-3 md:grid-cols-[1fr_180px_auto_auto]" @submit.prevent="saveProgram(department.id)">
            <Input id="program-name" v-model="programForm.name" placeholder="Program name" required />
            <Input id="program-code" v-model="programForm.code" placeholder="Code" required />
            <Button type="submit" variant="primary" :loading="saving">{{ programForm.id ? 'Save' : 'Create' }}</Button>
            <Button type="button" variant="text" @click="cancelProgram">Cancel</Button>
          </form>

          <div v-if="programLoading[department.id]" class="py-4 text-center"><Spinner /></div>
          <p v-else-if="!department.programs?.length" class="text-sm text-muted">No programs have been added.</p>
          <div v-else class="divide-y divide-border rounded border border-border">
            <div v-for="program in department.programs" :key="program.id" class="flex items-center gap-3 p-3">
              <span class="flex-1">{{ program.name }}</span>
              <span class="text-xs text-muted">{{ program.code }}</span>
              <Button variant="text" @click="startProgram(department.id, program)">Edit</Button>
              <Button variant="danger" @click="removeProgram(program)">Delete</Button>
            </div>
          </div>
        </div>
      </article>
    </div>
  </section>
</template>

<script setup lang="ts">
interface Department {
  id: number
  name: string
  code: string
  programs?: Program[]
}
interface Program {
  id: number
  name: string
  code: string
  department_id: number
}

const route = useRoute()
const departments = ref<Department[]>([])
const expanded = reactive<Record<number, boolean>>({})
const programLoading = reactive<Record<number, boolean>>({})
const loading = ref(false)
const saving = ref(false)
const departmentEditor = ref(false)
const programEditor = ref<number | null>(null)
const departmentForm = reactive<{ id?: number; name: string; code: string }>({ name: '', code: '' })
const programForm = reactive<{ id?: number; name: string; code: string }>({ name: '', code: '' })

const responseData = (res: any) => res.data?.data?.data ?? res.data?.data ?? []

async function fetchDepartments() {
  loading.value = true
  try {
    const res = await api.get('department/get', { params: { campus_id: route.params.id } })
    departments.value = responseData(res)
  } catch (error) {
    console.error('Failed to fetch departments:', error)
  } finally {
    loading.value = false
  }
}

async function togglePrograms(department: Department) {
  expanded[department.id] = !expanded[department.id]
  if (expanded[department.id] && !department.programs) await fetchPrograms(department)
}

async function fetchPrograms(department: Department) {
  programLoading[department.id] = true
  try {
    const res = await api.get('program/get', { params: { campus_id: route.params.id, department_id: department.id } })
    department.programs = responseData(res)
  } catch (error) {
    console.error('Failed to fetch programs:', error)
  } finally {
    programLoading[department.id] = false
  }
}

function startDepartment(department?: Department) {
  departmentEditor.value = true
  departmentForm.id = department?.id
  departmentForm.name = department?.name ?? ''
  departmentForm.code = department?.code ?? ''
}

function cancelDepartment() {
  departmentEditor.value = false
  delete departmentForm.id
}

async function saveDepartment() {
  saving.value = true
  try {
    const payload = { ...departmentForm, campus_id: Number(route.params.id) }
    if (departmentForm.id) await api.put(`department/update/${departmentForm.id}`, payload)
    else await api.post('department/create', payload)
    cancelDepartment()
    await fetchDepartments()
  } finally {
    saving.value = false
  }
}

async function removeDepartment(department: Department) {
  if (!window.confirm(`Delete ${department.name}? Programs under it may also be removed.`)) return
  await api.delete(`department/delete/${department.id}`)
  await fetchDepartments()
}

function startProgram(departmentId: number, program?: Program) {
  programEditor.value = departmentId
  programForm.id = program?.id
  programForm.name = program?.name ?? ''
  programForm.code = program?.code ?? ''
  expanded[departmentId] = true
}

function cancelProgram() {
  programEditor.value = null
  delete programForm.id
}

async function saveProgram(departmentId: number) {
  saving.value = true
  try {
    const payload = { ...programForm, department_id: departmentId }
    if (programForm.id) await api.put(`program/update/${programForm.id}`, payload)
    else await api.post('program/create', payload)
    const department = departments.value.find((item) => item.id === departmentId)
    cancelProgram()
    if (department) await fetchPrograms(department)
  } finally {
    saving.value = false
  }
}

async function removeProgram(program: Program) {
  if (!window.confirm(`Delete ${program.name}?`)) return
  await api.delete(`program/delete/${program.id}`)
  const department = departments.value.find((item) => item.id === program.department_id)
  if (department) await fetchPrograms(department)
}

onMounted(fetchDepartments)
</script>
