<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm">
    <div class="card-body">
      <h5>Compétences</h5>
      <div class="d-flex flex-wrap gap-2 mb-3">
        <span v-for="comp in competencies" :key="comp.id" class="badge-competency d-flex align-items-center">
          {{ comp.name }}
          <button class="btn btn-link btn-sm ms-2 p-0 text-danger" @click="removeCompetency(comp.id)" title="Supprimer">
            <X :size="18" />
          </button>
        </span>
        <span v-if="competencies.length === 0" class="text-secondary">Aucune compétence</span>
      </div>
      <button class="btn btn-outline-primary w-100" @click="openModal">
        Ajouter une compétence
      </button>
    </div>
  </div>

  <!-- Modal Vue native -->
  <div v-if="showModal">
    <div class="modal-backdrop fade show" style="z-index: 1050;" @click="closeModal" />
    <div class="modal d-block" tabindex="-1" style="z-index: 1055;">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Ajouter une compétence</h5>
            <button class="btn-close" @click="closeModal"></button>
          </div>
          <div class="modal-body">
            <label class="form-label">Rechercher une compétence existante</label>
            <input id="competency-search" v-model="search" class="form-control mb-2" placeholder="Rechercher..." />
            <select v-model="selectedCompetencyId" class="form-select mb-3">
              <option value="">-- Sélectionner une compétence --</option>
              <option v-for="comp in availableCompetencies" :key="comp.id" :value="comp.id">
                {{ comp.name }}
              </option>
            </select>
            <div class="text-center my-2">Ou</div>
            <label class="form-label">Ajouter une compétence personnalisée</label>
            <input v-model="customCompetency" class="form-control" placeholder="Nom de la compétence" @keyup.enter="addCompetency" />
            <div v-if="error" class="alert alert-danger mt-3 py-2 px-3">{{ error }}</div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" @click="closeModal">Annuler</button>
            <button class="btn btn-outline-primary" @click="addCompetency">Ajouter</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, nextTick } from 'vue'
import { X } from 'lucide-vue-next'

const competencies = ref<{id: number, name: string}[]>([])
const allCompetencies = ref<{id: number, name: string}[]>([])
const showModal = ref(false)
const selectedCompetencyId = ref('')
const customCompetency = ref('')
const error = ref('')
const search = ref('')

const availableCompetencies = computed(() =>
  allCompetencies.value.filter(
    c => !competencies.value.some(sel => sel.id === c.id) && c.name.toLowerCase().includes(search.value.toLowerCase())
  )
)

async function fetchCompetencies() {
  const t = localStorage.getItem('token')
  if (!t) return
  const res = await fetch(`${import.meta.env.VITE_API_URL}/student/competencies`, {
    headers: { Authorization: `Bearer ${t}` },
  })
  competencies.value = res.ok ? await res.json() : []
}

async function fetchAllCompetencies() {
  const res = await fetch(`${import.meta.env.VITE_API_URL}/competencies`)
  allCompetencies.value = res.ok ? await res.json() : []
}

function openModal() {
  error.value = ''
  selectedCompetencyId.value = ''
  customCompetency.value = ''
  search.value = ''
  showModal.value = true
  nextTick(() => {
    const el = document.getElementById('competency-search')
    if (el) el.focus()
  })
}

function closeModal() {
  showModal.value = false
}

async function addCompetency() {
  error.value = ''
  const t = localStorage.getItem('token')
  if (!t) return
  let name = ''
  if (selectedCompetencyId.value) {
    const comp = allCompetencies.value.find(c => c.id === Number(selectedCompetencyId.value))
    if (!comp) return
    name = comp.name
  } else if (customCompetency.value.trim()) {
    name = customCompetency.value.trim()
  } else {
    error.value = 'Veuillez sélectionner ou saisir une compétence.'
    return
  }
  const res = await fetch(`${import.meta.env.VITE_API_URL}/student/add-competency`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${t}` },
    body: JSON.stringify({ name })
  })
  if (res.ok) {
    await fetchCompetencies()
    closeModal()
  } else {
    const data = await res.json()
    error.value = data.error || "Erreur lors de l'ajout."
  }
}

async function removeCompetency(id: number) {
  error.value = ''
  const t = localStorage.getItem('token')
  if (!t) return
  const res = await fetch(`${import.meta.env.VITE_API_URL}/student/delete-competency/${id}`, {
    method: 'DELETE',
    headers: { Authorization: `Bearer ${t}` },
  })
  if (res.ok) {
    await fetchCompetencies()
  } else {
    const data = await res.json()
    error.value = data.error || 'Erreur lors de la suppression.'
  }
}

fetchCompetencies()
fetchAllCompetencies()
</script>

<style scoped>
.badge-competency {
  background: #f4f7fe;
  border: 1px solid #e3e8f7;
  color: #222;
  border-radius: 999px;
  padding: 0.5em 1.1em;
  font-size: 1rem;
  margin-bottom: 0.3em;
  margin-right: 0.3em;
  box-shadow: 0 2px 8px 0 #e3e8f7;
  transition: box-shadow 0.2s;
}
.badge-competency:hover {
  box-shadow: 0 4px 16px 0 #dbeafe;
}
.btn-link {
  text-decoration: none;
  font-size: 1.2em;
  line-height: 1;
}
.modal-backdrop {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: #0008;
}
.modal {
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style> 