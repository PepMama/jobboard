<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import { Trash2, Eye } from 'lucide-vue-next'

interface Company {
  id: number
  name: string
  city: string
  industry: string
  logo: string
  website: string
}

interface Offer {
  id: number
  title: string
  description: string
  contract_type: string
  salary: number | null
  city: string
  remote: boolean
  start_date: string
  company: Company
  liked_at: string
}

const showSidebar = ref(true)
const likedOffers = ref<Offer[]>([])
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(() => {
  fetchLikedOffers()
})

async function fetchLikedOffers() {
  loading.value = true
  error.value = null
  try {
    const token = localStorage.getItem('token')
    if (!token) throw new Error('Token non trouvé')
    const response = await fetch('https://localhost:8000/student/liked-offers', {
      headers: { Authorization: `Bearer ${token}` },
    })
    if (!response.ok) throw new Error(`Erreur ${response.status}`)
    const data = await response.json()
    likedOffers.value = data
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Erreur inconnue'
  } finally {
    loading.value = false
  }
}

async function removeLike(offerId: number) {
  try {
    const token = localStorage.getItem('token')
    if (!token) throw new Error('Token non trouvé')
    const response = await fetch(`https://localhost:8000/student/unlike-offer/${offerId}`, {
      method: 'DELETE',
      headers: { Authorization: `Bearer ${token}` },
    })
    if (!response.ok) throw new Error(`Erreur ${response.status}`)
    likedOffers.value = likedOffers.value.filter(offer => offer.id !== offerId)
  } catch (err) {
    console.error('Erreur lors de la suppression du like:', err)
  }
}

function viewOffer(offerId: number) {
  window.open(`/offer/${offerId}`)
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
        <div v-else-if="likedOffers.length === 0" class="text-center py-5">
          <h4 class="text-muted">Aucune offre likée</h4>
          <p class="text-muted">Vous n'avez pas encore liké d'offres.</p>
        </div>
        <div v-else class="row g-4">
          <div v-for="offer in likedOffers" :key="offer.id" class="col-12 col-md-6 col-lg-4">
            <div class="card bg-white shadow-sm h-100">
              <div class="card-body d-flex flex-column">
                <div class="d-flex align-items-center mb-3">
                  <img 
                    :src="offer.company.logo || '/src/assets/logo.png'" 
                    :alt="offer.company.name"
                    class="rounded-circle me-3"
                    width="60"
                    height="60"
                    style="object-fit: cover"
                  />
                  <div class="flex-grow-1">
                    <h5 class="mb-1">{{ offer.title }}</h5>
                    <p class="text-muted mb-0">{{ offer.company.name }}</p>
                    <small class="text-primary">{{ offer.company.industry }}</small>
                  </div>
                  <div class="d-flex gap-2">
                    <router-link 
                      :to="`/offer/${offer.id}`"
                      class="btn btn-outline-primary btn-sm"
                      title="Voir l'offre"
                    >
                      <Eye :size="16" />
                    </router-link>
                    <button 
                      @click="removeLike(offer.id)"
                      class="btn btn-outline-danger btn-sm"
                      title="Retirer le like"
                    >
                      <Trash2 :size="16" />
                    </button>
                  </div>
                </div>
                <p class="text-muted small mb-3">
                  {{ offer.description?.substring(0, 100) }}{{ offer.description?.length > 100 ? '...' : '' }}
                </p>
                <div class="mt-auto">
                  <div class="d-flex gap-2 mb-2">
                    <a v-if="offer.company.website" :href="offer.company.website" target="_blank" class="btn btn-outline-primary btn-sm">
                      Site Web
                    </a>
                  </div>
                  <small class="text-muted">
                    Liké le {{ new Date(offer.liked_at).toLocaleDateString('fr-FR') }}
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