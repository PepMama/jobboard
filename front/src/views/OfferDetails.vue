<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'

const route = useRoute()
const router = useRouter()
const showSidebar = ref(true)
const offer = ref<any>(null)
const loading = ref(true)
const error = ref<string | null>(null)

onMounted(() => {
  fetchOffer()
})

async function fetchOffer() {
  loading.value = true
  error.value = null
  try {
    const token = localStorage.getItem('token')
    if (!token) throw new Error('Token non trouvé')
    const id = route.params.id
    const response = await fetch(`https://127.0.0.1:8000/offer/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    if (!response.ok) throw new Error('Erreur ' + response.status)
    offer.value = await response.json()
  } catch (err: any) {
    error.value = err.message || 'Erreur inconnue'
  } finally {
    loading.value = false
  }
}

function formatDate(date: string) {
  if (!date) return ''
  return new Date(date).toLocaleDateString('fr-FR')
}
</script>

<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Détail de l'offre" @toggle-sidebar="showSidebar = true" />
      <div class="container-fluid">
        <div v-if="loading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Chargement...</span>
          </div>
        </div>
        <div v-else-if="error" class="alert alert-danger">
          {{ error }}
        </div>
        <div v-else-if="!offer" class="text-center py-5">
          <h4 class="text-muted">Offre introuvable</h4>
        </div>
        <div v-else class="row justify-content-center">
          <div class="col-12 col-md-8 col-lg-6">
            <div class="card bg-white shadow-sm p-4">
              <div class="d-flex align-items-center mb-4">
                <img :src="offer.company?.logo || '/src/assets/logo.png'" :alt="offer.company?.name" class="rounded-circle me-3" width="60" height="60" style="object-fit: cover" />
                <div>
                  <h2 class="mb-1">{{ offer.title }}</h2>
                  <h5 class="text-muted mb-0">{{ offer.company?.name }}</h5>
                  <small class="text-primary">{{ offer.company?.industry }}</small>
                </div>
              </div>
              <p class="mb-3"><strong>Description :</strong><br />{{ offer.description }}</p>
              <p><strong>Type de contrat :</strong> {{ offer.contract_type }}</p>
              <p><strong>Salaire :</strong> {{ offer.salary ? offer.salary + ' €' : 'Non précisé' }}</p>
              <p><strong>Ville :</strong> {{ offer.city || 'Non précisé' }}</p>
              <p><strong>Télétravail :</strong> {{ offer.remote ? 'Oui' : 'Non' }}</p>
              <p><strong>Date de début :</strong> {{ formatDate(offer.start_date) }}</p>
              <div class="mt-4">
                <a v-if="offer.company?.website" :href="offer.company.website" target="_blank" class="btn btn-outline-primary btn-sm me-2">Site de l'entreprise</a>
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
</style> 