<script setup lang="ts">
import { ref, computed } from 'vue'
import { Trash2 } from 'lucide-vue-next'
import type { Education } from '@/types/student'
import SelectField from '@/components/Students/SelectField.vue'

const props = defineProps<{ educations?: Education[] }>()
const emit = defineEmits<{ (e: 'changed'): void }>()

const list = computed(() => props.educations ?? [])

const draft = ref<Omit<Education, 'id'>>({
  schoolName: '',
  degree: '',
  fieldOfStudy: '',
  startDate: '',
  endDate: '',
})

const fmt = (v?: string) => (v ? v.slice(0, 10) : '…')
const reset = () =>
  (draft.value = { schoolName: '', degree: '', fieldOfStudy: '', startDate: '', endDate: '' })

const degreeOptions = [
  { value: 'BAC +1', label: 'BAC +1' },
  { value: 'BAC +2', label: 'BAC +2' },
  { value: 'BAC +3', label: 'BAC +3' },
  { value: 'BAC +4', label: 'BAC +4' },
  { value: 'BAC +5', label: 'BAC +5' },
]

const fieldOfStudyOptions = [
  { value: 'Informatique & Numérique', label: 'Informatique & Numérique' },
  { value: 'Santé & Médecine', label: 'Santé & Médecine' },
  { value: 'Commerce', label: 'Commerce' },
  { value: 'Management & Marketing', label: 'Management & Marketing' },
  { value: 'Ingénierie & Sciences de l’Industrie', label: 'Ingénierie & Sciences de l’Industrie' },
  { value: 'Droit', label: 'Droit' },
  { value: 'Sciences Économiques & Gestion', label: 'Sciences Économiques & Gestion' },
  { value: 'Sciences Politiques & Relations Internationales', label: 'Sciences Politiques & Relations Internationales' },
  { value: 'Architecture & Urbanisme', label: 'Architecture & Urbanisme' },
  { value: 'Sciences Sociales & Psychologie', label: 'Sciences Sociales & Psychologie' },
  { value: 'Arts, Design & Communication Visuelle', label: 'Arts, Design & Communication Visuelle' },
  { value: 'Agriculture', label: 'Agriculture' },
  { value: 'Electromécanique', label: 'Electromécanique' },
]


async function save() {
  const token = localStorage.getItem('token')
  if (!token) return
  await fetch('https://127.0.0.1:8000/student/manage-education', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
    body: JSON.stringify(draft.value),
  }).then((r) => r.ok && emit('changed'))
  reset()
  ;(window as any).bootstrap?.Modal.getOrCreateInstance('#eduM')?.hide()
}

async function del(id: number) {
  const token = localStorage.getItem('token')
  if (!token) return
  await fetch(`https://127.0.0.1:8000/student/delete-education/${id}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${token}` },
  }).then((r) => r.ok && emit('changed'))
}
</script>

<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm">
    <div class="card-body">
      <h5>Formations</h5>

      <ul class="list-group list-group-flush">
        <li
          v-for="e in list"
          :key="e.id"
          class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 bg-list-items rounded-3 shadow-sm mb-2"
        >
          <div>
            <strong>{{ e.degree }}</strong> – {{ e.schoolName }}
            <small class="d-block">{{ e.fieldOfStudy }}</small>
            <small class="text-muted">{{ fmt(e.startDate) }} → {{ fmt(e.endDate) }}</small>
          </div>

          <button
            class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center rounded-circle"
            style="width: 32px; height: 32px"
            @click="del(e.id)"
          >
            <Trash2 />
          </button>
        </li>
      </ul>

      <button
        class="btn btn-outline-primary mt-3 w-100"
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
            <input
              v-model="draft.schoolName"
              class="form-control mb-2"
              placeholder="Établissement"
            />

            <SelectField
              v-model="draft.degree"
              :options="degreeOptions"
              label="Diplôme"
              placeholder="Choisissez un diplôme"
            />

            <SelectField
              v-model="draft.fieldOfStudy"
              :options="fieldOfStudyOptions"
              label="Domaine d'étude"
              placeholder="Choisissez un domaine d'étude"
            />
            <input v-model="draft.startDate" type="date" class="form-control mb-2" />
            <input v-model="draft.endDate" type="date" class="form-control mb-2" />
            <button class="btn btn-outline-primary w-100">Enregistrer</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
