<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import { Trash2, Eye } from 'lucide-vue-next'

interface CompanyInfo {
    id: number
    name: string
    industry: string
    logo: string | null
    city: string
    description?: string
    linkedin?: string
    website?: string
}

interface Company {
  id: number
  isContacted: boolean
  matchId: number
  company: CompanyInfo
  matchedAt: string
}

const showSidebar = ref(true)
const matchedCompanies = ref<Company[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(() => {
  fetchMatchedCompanies()
})

async function fetchMatchedCompanies() {
  loading.value = true
  error.value = null
  
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }

    const response = await fetch('https://localhost:8000/matches/student', {
      headers: { Authorization: `Bearer ${token}` },
    })

    if (!response.ok) {
      throw new Error(`Erreur ${response.status}`)
    }

    const data = await response.json()

    console.log(data)
    matchedCompanies.value = data
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Erreur inconnue'
  } finally {
    loading.value = false
  }
}

function viewProfile(companyName: string) {
  window.open(`/company/${encodeURIComponent(companyName)}`, '_blank')
}
</script>

<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Mes matchs" @toggle-sidebar="showSidebar = true" />

      <div class="container-fluid">
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
        </div>

        <div v-else-if="error" class="alert alert-danger">
          {{ error }}
        </div>

        <div v-else-if="matchedCompanies.length === 0" class="text-center py-5">
          <h4 class="text-muted">Aucune entreprise matchée</h4>
          <p class="text-muted">Vous n'avez pas encore matché d'entreprises.</p>
        </div>

        <div v-else class="row g-4">
          <div v-for="company in matchedCompanies" :key="company.id" class="col-12 col-md-6 col-lg-4">
            <div class="card bg-white shadow-sm h-100">
              <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                  <img 
                    :src="company.company.logo || '/src/assets/avatar-defaut.jpg'" 
                    :alt="`${company.company.name} logo`"
                    class="rounded-circle me-3"
                    width="60"
                    height="60"
                    style="object-fit: cover"
                  />
                  <div class="flex-grow-1">
                    <h5 class="mb-1">{{ company.company.name }}</h5>
                    <p class="text-muted mb-0">{{ company.company.city }}</p>
                  </div>
                  <div class="d-flex gap-2">
                    <button 
                      @click="viewProfile(`${company.company.name}`)"
                      class="btn btn-outline-primary btn-sm"
                      title="Voir le profil"
                    >
                      <Eye :size="16" />
                    </button>
                  </div>
                </div>

                <p v-if="company.company.description" class="text-muted small mb-3">
                  {{ company.company.description?.substring(0, 100) }}{{ company.company.description?.length > 100 ? '...' : '' }}
                </p>

                <div class="mt-auto">
                  <div class="d-flex gap-2 mb-2">
                    <a v-if="company.company.linkedin" :href="company.company.linkedin" class="btn btn-outline-primary btn-sm">
                      LinkedIn
                    </a>
                    <a v-if="company.company.website" :href="company.company.website" class="btn btn-outline-dark btn-sm">
                        Website
                    </a>
                  </div>
                  <small class="text-muted">
                    Matché le {{ new Date(company.matchedAt).toLocaleDateString('fr-FR') }}
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
