<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import { Trash2, Eye } from 'lucide-vue-next'

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

    const response = await fetch('https://localhost:8000/company/liked-students', {
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

    const response = await fetch(`https://localhost:8000/company/unlike-student/${studentId}`, {
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
  window.open(`/student/${encodeURIComponent(studentName)}`, '_blank')
}
</script>

<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Mes Likes" @toggle-sidebar="showSidebar = true" />

      <div class="container-fluid">
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
                    <a v-if="student.linkedin" :href="student.linkedin" target="_blank" class="btn btn-outline-primary btn-sm">
                      LinkedIn
                    </a>
                    <a v-if="student.github" :href="student.github" target="_blank" class="btn btn-outline-dark btn-sm">
                      GitHub
                    </a>
                    <a v-if="student.cv" :href="student.cv" target="_blank" class="btn btn-outline-success btn-sm">
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