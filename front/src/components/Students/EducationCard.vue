<script setup lang="ts">
import { ref, computed } from 'vue'
import { Trash2 }        from 'lucide-vue-next'
import type { Education } from '@/types/student'

const props = defineProps<{ educations?: Education[] }>()
const emit  = defineEmits<{ (e:'changed'): void }>()

const list = computed(() => props.educations ?? [])

const draft = ref<Omit<Education, 'id'>>({
  schoolName: '',
  degree: '',
  fieldOfStudy: '',
  startDate: '',
  endDate: ''
})

const fmt   = (v?: string) => v ? v.slice(0, 10) : '…'
const reset = () =>
  (draft.value = { schoolName:'', degree:'', fieldOfStudy:'', startDate:'', endDate:'' })

async function save () {
  const token = localStorage.getItem('token')
  if (!token) return
  await fetch('https://localhost:8000/student/manage-education', {
    method: 'POST',
    headers: { 'Content-Type':'application/json', Authorization:`Bearer ${token}` },
    body:   JSON.stringify(draft.value)
  }).then(r => r.ok && emit('changed'))
  reset()
  ;(window as any).bootstrap?.Modal.getOrCreateInstance('#eduM')?.hide()
}

async function del (id: number) {
  const token = localStorage.getItem('token')
  if (!token) return
  await fetch(`https://localhost:8000/student/delete-education/${id}`, {
    method:'DELETE', headers:{ Authorization:`Bearer ${token}` }
  }).then(r => r.ok && emit('changed'))
}
</script>

<template>
  <div class="card bg-white shadow-sm mt-3">
    <div class="card-body">
      <h5>Formations</h5>

      <ul class="list-group list-group-flush">
        <li
          v-for="e in list"
          :key="e.id"
          class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 bg-success-subtle border-0 rounded-3 shadow-sm mb-2"
        >
          <div>
            <strong>{{ e.degree }}</strong> – {{ e.schoolName }}
            <small class="d-block">{{ e.fieldOfStudy }}</small>
            <small class="text-muted">{{ fmt(e.startDate) }} → {{ fmt(e.endDate) }}</small>
          </div>

          <button
            class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center rounded-circle"
            style="width:32px;height:32px"
            @click="del(e.id)"
          >
            <Trash2 />
          </button>
        </li>
      </ul>
      
      <button
        class="btn btn-success mt-3 w-100"
        data-bs-toggle="modal"
        data-bs-target="#eduM"
      >
        Ajouter une formation
      </button>
    </div>
  </div>

  <div class="modal fade" id="eduM" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nouvelle formation</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">
          <form @submit.prevent="save">
            <input v-model="draft.schoolName" class="form-control mb-2" placeholder="Établissement">
            <input v-model="draft.degree" class="form-control mb-2" placeholder="Diplôme">
            <input v-model="draft.fieldOfStudy" class="form-control mb-2" placeholder="Spécialité">
            <input v-model="draft.startDate" type="date" class="form-control mb-2">
            <input v-model="draft.endDate" type="date" class="form-control mb-2">
            <button class="btn btn-success w-100">Enregistrer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
