<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import { Trash2, Eye, Search } from 'lucide-vue-next'

interface Student {
  id: number
  firstname: string
  name: string
  city: string
  description: string
  photo: string
  linkedin: string
  github: string
  cv: string
  liked_at: string
}

const showSidebar = ref(true)
const likedStudents = ref<Student[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

const searchName = ref('')
const searchCity = ref('')

const searchLoading = ref(false)

onMounted(() => {
  fetchLikedStudents()
})

async function fetchLikedStudents() {
  loading.value = true
  error.value = null
  
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }

    const params = new URLSearchParams()
    if (searchName.value) params.append('name', searchName.value)
    if (searchCity.value) params.append('city', searchCity.value)
    
    const url = `${import.meta.env.VITE_API_URL}/company/liked-students${params.toString() ? '?' + params.toString() : ''}`
    
    const response = await fetch(url, {
      headers: { Authorization: `Bearer ${token}` },
    })

    if (!response.ok) {
      throw new Error(`Erreur ${response.status}`)
    }

    const data = await response.json()
    likedStudents.value = data
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Erreur inconnue'
  } finally {
    loading.value = false
  }
}

async function removeLike(studentId: number) {
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }

    const response = await fetch(`${import.meta.env.VITE_API_URL}/company/unlike-student/${studentId}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token}` },
    })

    if (!response.ok) {
      throw new Error(`Erreur ${response.status}`)
    }

    // Retirer l'étudiant de la liste
    likedStudents.value = likedStudents.value.filter(student => student.id !== studentId)
  } catch (err) {
    console.error('Erreur lors de la suppression du like:', err)
  }
}

function viewProfile(studentName: string) {
  window.location.href = `/student/${encodeURIComponent(studentName)}`
}

watch([searchName, searchCity], () => {
  fetchLikedStudents()
}, { deep: true })

function clearFilters() {
  searchName.value = ''
  searchCity.value = ''
}
</script>

<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Mes Likes" @toggle-sidebar="showSidebar = true" />

      <div class="container-fluid">
        <div class="card bg-white shadow-sm mb-4">
          <div class="card-body">
            <h6 class="card-title mb-3">
              <Search :size="20" class="me-2" />
              Rechercher dans mes likes
            </h6>
            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label small text-muted">Nom et prénom de l'étudiant</label>
                <input 
                  v-model="searchName"
                  type="text" 
                  class="form-control form-control-sm"
                  placeholder="Rechercher par nom ou prénom..."
                />
              </div>
              <div class="col-md-6">
                <label class="form-label small text-muted">Ville</label>
                <input 
                  v-model="searchCity"
                  type="text" 
                  class="form-control form-control-sm"
                  placeholder="Rechercher par ville..."
                />
              </div>
            </div>
            <div class="mt-3">
              <button 
                @click="clearFilters"
                class="btn btn-outline-secondary btn-sm"
              >
                Effacer les filtres
              </button>
              <span class="ms-3 text-muted small">
                {{ likedStudents.length }} étudiant(s) trouvé(s)
              </span>
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

        <div v-else-if="likedStudents.length === 0" class="text-center py-5">
          <h4 class="text-muted">Aucun étudiant liké</h4>
          <p class="text-muted">Vous n'avez pas encore liké d'étudiants.</p>
        </div>

        <div v-else-if="likedStudents.length === 0" class="text-center py-5">
          <h4 class="text-muted">Aucun résultat</h4>
          <p class="text-muted">Aucun étudiant ne correspond à vos critères de recherche.</p>
        </div>

        <div v-else class="row g-4">
          <div v-for="student in likedStudents" :key="student.id" class="col-12 col-md-6 col-lg-4">
            <div class="card bg-white shadow-sm h-100">
              <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                  <img 
                    :src="student.photo || '/src/assets/avatar-defaut.jpg'" 
                    :alt="`${student.firstname} ${student.name}`"
                    class="rounded-circle me-3"
                    width="60"
                    height="60"
                    style="object-fit: cover"
                  />
                  <div class="flex-grow-1">
                    <h5 class="mb-1">{{ student.firstname }} {{ student.name }}</h5>
                    <p class="text-muted mb-0">{{ student.city }}</p>
                  </div>
                  <div class="d-flex gap-2">
                    <button 
                      @click="viewProfile(`${student.firstname}-${student.name}`)"
                      class="btn btn-outline-primary btn-sm"
                      title="Voir le profil"
                    >
                      <Eye :size="16" />
                    </button>
                    <button 
                      @click="removeLike(student.id)"
                      class="btn btn-outline-danger btn-sm"
                      title="Retirer le like"
                    >
                      <Trash2 :size="16" />
                    </button>
                  </div>
                </div>

                <p class="text-muted small mb-3">
                  {{ student.description?.substring(0, 100) }}{{ student.description?.length > 100 ? '...' : '' }}
                </p>

                <div class="mt-auto">
                  <div class="d-flex gap-2 mb-2">
                    <span v-if="student.city" class="badge bg-secondary">{{ student.city }}</span>
                  </div>
                  <div class="d-flex gap-2 mb-2">
                    <a v-if="student.linkedin" :href="student.linkedin" class="btn btn-outline-primary btn-sm">
                      LinkedIn
                    </a>
                    <a v-if="student.github" :href="student.github" class="btn btn-outline-dark btn-sm">
                      GitHub
                    </a>
                    <a v-if="student.cv" :href="student.cv" class="btn btn-outline-success btn-sm">
                      CV
                    </a>
                  </div>
                  <small class="text-muted">
                    Liké le {{ new Date(student.liked_at).toLocaleDateString('fr-FR') }}
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