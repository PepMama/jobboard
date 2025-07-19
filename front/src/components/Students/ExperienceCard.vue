<script setup lang="ts">
import { ref } from 'vue'
import { Trash2 } from 'lucide-vue-next'
import type { Experience } from '@/types/student'
import { defineProps } from 'vue'

const props = defineProps<{ experiences: Experience[] }>()
const emit = defineEmits<{ (e: 'changed'): void }>()

const draft = ref<Omit<Experience, 'id'>>({
  companyName: '',
  jobTitle: '',
  description: '',
  startDate: '',
  endDate: '',
})

const fmt = (d?: string) => (d ? d.slice(0, 10) : '…')

const reset = () =>
  (draft.value = {
    companyName: '',
    jobTitle: '',
    description: '',
    startDate: '',
    endDate: '',
  })

async function save() {
  const token = localStorage.getItem('token')
  if (!token) return
  const res = await fetch('https://localhost:8000/student/manage-experience', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${token}` },
    body: JSON.stringify(draft.value),
  })
  if (!res.ok) return console.error(await res.text())
  reset()
  emit('changed')
  ;(window as any).bootstrap?.Modal.getOrCreateInstance('#expModal')?.hide()
}

async function del(id: number) {
  const t = localStorage.getItem('token')
  if (!t) return
  const res = await fetch(`https://localhost:8000/student/delete-experience/${id}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${t}` },
  })
  if (res.ok) emit('changed')
}
</script>

<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm">
    <div class="card-body">
      <h5>Expériences</h5>
      <ul class="list-group list-group-flush">
        <li
          v-for="exp in experiences"
          :key="exp.id"
          class="list-group-item d-flex justify-content-between align-items-center py-3 px-4 bg-list-items rounded-3 shadow-sm mb-2"
        >
          <div class="pe-2">
            <div class="fw-semibold">{{ exp.jobTitle }}</div>
            <div class="text-uppercase small">{{ exp.companyName }}</div>
            <div class="text-muted small">{{ fmt(exp.startDate) }} → {{ fmt(exp.endDate) }}</div>
          </div>

          <button
            class="btn btn-outline-danger btn-sm d-flex align-items-center justify-content-center rounded-circle"
            style="width: 32px; height: 32px"
            @click="del(exp.id)"
          >
            <Trash2 />
          </button>
        </li>
      </ul>
      <button
        class="btn btn-outline-primary mt-3"
        data-bs-toggle="modal"
        data-bs-target="#expModal"
      >
        Ajouter une expérience
      </button>
    </div>
  </div>

  <div class="modal fade" id="expModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Nouvelle expérience</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="save">
            <input v-model="draft.companyName" class="form-control mb-2" placeholder="Entreprise" />
            <input v-model="draft.jobTitle" class="form-control mb-2" placeholder="Poste" />
            <textarea
              v-model="draft.description"
              class="form-control mb-2"
              placeholder="Description"
            ></textarea>
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
