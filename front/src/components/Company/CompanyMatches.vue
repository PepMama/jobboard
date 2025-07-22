<script setup lang="ts">
import { ref, onMounted, computed } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import { Trash2, Eye, Send, Search } from 'lucide-vue-next'

interface StudentInfo {
  city: string
  id: number
  name: string
  photo: string | null
  description?: string
  linkedin?: string
  github?: string
  cv?: string
  email?: string
}

interface JobInfo {
  id: number
  title: string
  city: string
  contractType: string
  salary?: number
  remote: boolean
  description?: string
  type: string
}

interface Student {
  id: number
  isContacted: boolean
  matchId: number
  student: StudentInfo
  job: JobInfo
  matchedAt: string
}

const showSidebar = ref(true)
const matchedStudents = ref<Student[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const deleting = ref<number | null>(null)

const searchName = ref('')
const searchCity = ref('')
const searchOffer = ref('')
function clearFilters() {
  searchName.value = ''
  searchCity.value = ''
  searchOffer.value = ''
}
const filteredStudents = computed(() => {
  return matchedStudents.value.filter(student => {
    const matchName = student.student.name.toLowerCase().includes(searchName.value.toLowerCase())
    const matchCity = student.student.city.toLowerCase().includes(searchCity.value.toLowerCase())
    const matchOffer = student.job.title.toLowerCase().includes(searchOffer.value.toLowerCase())
    return matchName && matchCity && matchOffer
  })
})

onMounted(() => {
  fetchMatchedStudents()
})

async function fetchMatchedStudents() {
  loading.value = true
  error.value = null
  
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }

    const response = await fetch(`${import.meta.env.VITE_API_URL}/matches/company`, {
      headers: { Authorization: `Bearer ${token}` },
    })

    if (!response.ok) {
      throw new Error(`Erreur ${response.status}`)
    }

    const data = await response.json()

    console.log(data)
    matchedStudents.value = data
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Erreur inconnue'
  } finally {
    loading.value = false
  }
}

async function deleteMatch(matchId: number) {
  if (!confirm('Voulez-vous vraiment supprimer ce match ?')) return;
  const token = localStorage.getItem('token');
  if (!token) return;
  deleting.value = matchId;
  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/matches/${matchId}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token}` },
    });
    if (response.ok) {
      matchedStudents.value = matchedStudents.value.filter(m => m.matchId !== matchId);
    } else {
      alert('Erreur lors de la suppression du match');
    }
  } catch (e) {
    alert('Erreur réseau lors de la suppression du match');
  } finally {
    deleting.value = null;
  }
}

function viewProfile(studentName: string) {
  window.location.href = `/student/${encodeURIComponent(studentName)}`
}
</script>

<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Mes matchs" @toggle-sidebar="showSidebar = true" />

      <div class="container-fluid">
        <div class="card bg-white shadow-sm mb-4">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <Search :size="20" class="me-2" />
              Rechercher dans mes matchs
            </h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small text-muted">Nom ou prénom de l'étudiant</label>
                <input v-model="searchName" type="text" class="form-control form-control-sm" placeholder="Rechercher par nom ou prénom..." />
              </div>
              <div class="col-md-4">
                <label class="form-label small text-muted">Ville</label>
                <input v-model="searchCity" type="text" class="form-control form-control-sm" placeholder="Rechercher par ville..." />
              </div>
              <div class="col-md-4">
                <label class="form-label small text-muted">Nom de l'offre</label>
                <input v-model="searchOffer" type="text" class="form-control form-control-sm" placeholder="Rechercher par offre..." />
              </div>
            </div>
            <div class="mt-3">
              <button @click="clearFilters" class="btn btn-outline-secondary btn-sm">Effacer les filtres</button>
              <span class="ms-3 text-muted small">{{ filteredStudents.length }} match(s) trouvé(s)</span>
            </div>
          </div>
        </div>
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
        </div>

        <div v-else-if="error" class="alert alert-danger">
          {{ error }}
        </div>

        <div v-else-if="matchedStudents.length === 0" class="text-center py-5">
          <h4 class="text-muted">Aucun étudiant matché</h4>
          <p class="text-muted">Vous n'avez pas encore matché d'étudiants.</p>
        </div>

        <div v-else class="row g-4">
          <div v-for="student in filteredStudents" :key="student.id" class="col-12 col-md-6 col-lg-4">
            <div class="card bg-white shadow-sm h-100">
              <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                  <img 
                    :src="student.student.photo || '/src/assets/avatar-defaut.jpg'" 
                    :alt="`${student.student.name} photo`"
                    class="rounded-circle me-3"
                    width="60"
                    height="60"
                    style="object-fit: cover"
                  />
                  <div class="flex-grow-1">
                    <h5 class="mb-1">{{ student.student.name }}</h5>
                    <p class="text-muted mb-0">{{ student.student.city }}</p>
                  </div>
                  <div class="d-flex gap-2">
                    <button 
                      @click="viewProfile(`${student.student.name}`)"
                      class="btn btn-outline-primary btn-sm"
                      title="Voir le profil"
                    >
                      <Eye :size="16" />
                    </button>
                    <button
                      @click="deleteMatch(student.matchId)"
                      class="btn btn-outline-danger btn-sm"
                      :disabled="deleting === student.matchId"
                      title="Supprimer le match"
                    >
                      <Trash2 :size="16" />
                    </button>
                  </div>
                </div>

                <p v-if="student.student.description">
                  {{ student.student.description?.substring(0, 100) }}{{ student.student.description?.length > 100 ? '...' : '' }}
                </p>

                <p class="text-muted small mb-3">A postulé à l'offre {{ student.job.title }}</p>

                <div class="mt-auto">
                  <div class="d-flex gap-2 mb-2">
                    <a v-if="student.student.linkedin" :href="student.student.linkedin" class="btn btn-outline-primary btn-sm">
                      LinkedIn
                    </a>
                    <a v-if="student.student.github" :href="student.student.github" class="btn btn-outline-dark btn-sm">
                      GitHub
                    </a>
                    <a v-if="student.student.cv" :href="student.student.cv" class="btn btn-outline-success btn-sm">
                      CV
                    </a>
                    <a
                      v-if="student.student.email"
                      :href="`mailto:${student.student.email}?subject=${encodeURIComponent(student.job.title)}`"
                      class="btn btn-outline-success btn-sm"
                      title="Contacter l'étudiant"
                    >
                      <Send :size="16" class="me-1" /> Envoyer un mail
                    </a>
                  </div>
                  <small class="text-muted">
                    Matché le {{ new Date(student.matchedAt).toLocaleDateString('fr-FR') }}
                  </small>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.dashboard-bg {
  background: #f7faff;
}
.card {
  border-radius: 18px !important;
  box-shadow: 0 2px 16px 0 #e3e8f7 !important;
  border: none !important;
  transition: transform 0.2s ease-in-out;
}
.card:hover {
  transform: translateY(-2px);
}
@media (max-width: 991px) {
  .container-fluid .row > div {
    margin-bottom: 1.5rem;
  }
}
.flex-grow-1 {
  overflow-y: auto;
  max-height: 100vh;
}
</style> 
