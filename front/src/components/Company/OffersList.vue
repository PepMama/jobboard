<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Mes offres d'emploi" @toggle-sidebar="showSidebar = true" />
      
      <div class="mb-4">
        <button 
          @click="createNewOffer" 
          class="btn btn-primary d-flex align-items-center gap-2"
          style="background-color: #5651ab; border-color: #5651ab;"
        >
          <Plus :size="20" />
          Créer une nouvelle offre
        </button>
      </div>

      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="mt-3 text-muted">Chargement de vos offres...</p>
      </div>

      <div v-else-if="offers.length === 0" class="text-center py-5">
        <div class="mb-4">
          <Briefcase :size="64" class="text-muted" />
        </div>
        <h4 class="text-muted mb-3">Aucune offre créée</h4>
        <p class="text-muted mb-4">
          Vous n'avez pas encore créé d'offres d'emploi. 
          Commencez par créer votre première offre !
        </p>
        <button 
          @click="createNewOffer" 
          class="btn btn-primary"
          style="background-color: #5651ab; border-color: #5651ab;"
        >
          Créer ma première offre
        </button>
      </div>

      <div v-else class="row g-4">
        <div 
          v-for="offer in offers" 
          :key="offer.id" 
          class="col-12 col-md-6 col-lg-4"
        >
          <div class="card h-100 shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-start mb-3">
                <h5 class="card-title mb-0 text-truncate">{{ offer.title }}</h5>
                <div class="d-flex gap-2">
                  <button 
                    @click="editOffer(offer)"
                    class="btn btn-outline-primary btn-sm"
                    title="Modifier l'offre"
                  >
                    <Edit :size="14" />
                  </button>
                  <button 
                    @click="deleteOffer(offer.id)"
                    class="btn btn-outline-danger btn-sm"
                    title="Supprimer l'offre"
                  >
                    <Trash2 :size="14" />
                  </button>
                </div>
              </div>

              <p class="card-text text-muted mb-3">
                {{ truncateDescription(offer.description) }}
              </p>

              <div class="mb-3">
                <span class="badge bg-primary me-2">{{ offer.contractType }}</span>
                <span v-if="offer.remote" class="badge bg-success">Télétravail</span>
              </div>

              <div class="row text-muted small mb-3">
                <div class="col-6">
                  <MapPin :size="14" class="me-1" />
                  {{ offer.city }}
                </div>
                <div class="col-6 text-end">
                  <Euro :size="14" class="me-1" />
                  {{ formatSalary(offer.salary) }}
                </div>
              </div>

              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                  <Calendar :size="14" class="me-1" />
                  {{ formatDate(offer.startDate) }}
                </small>
                <span 
                  :class="getStatusBadgeClass(offer.state)"
                  class="badge"
                >
                  {{ offer.state }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Confirmation modal for delete -->
      <div v-if="showDeleteModal" class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirmer la suppression</h5>
              <button type="button" class="btn-close" @click="closeDeleteModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              Êtes-vous sûr de vouloir supprimer cette offre ? Cette action est irréversible.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeDeleteModal">Annuler</button>
              <button 
                type="button" 
                class="btn btn-danger" 
                @click="confirmDelete"
                :disabled="deleting"
              >
                <span v-if="deleting" class="spinner-border spinner-border-sm me-2"></span>
                Supprimer
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { 
  Plus, 
  Briefcase, 
  Edit, 
  Trash2, 
  MapPin, 
  Euro, 
  Calendar 
} from 'lucide-vue-next'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'

interface JobOffer {
  id: number
  title: string
  description: string
  state: string
  contractType: string
  salary: number
  city: string
  remote: boolean
  startDate: string
}

const router = useRouter()
const showSidebar = ref(true)
const offers = ref<JobOffer[]>([])
const loading = ref(true)
const error = ref<string | null>(null)
const deleting = ref(false)
const offerToDelete = ref<number | null>(null)
const showDeleteModal = ref(false)

onMounted(() => {
  loadOffers()
})

async function loadOffers() {
  loading.value = true
  error.value = null
  
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }
    const response = await fetch('http://localhost:8000/company/offers', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error('Erreur lors du chargement des offres')
    }

    const data = await response.json()
    offers.value = data
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Une erreur est survenue'
  } finally {
    loading.value = false
  }
}

function createNewOffer() {
  router.push('/company/create-offer')
}

function editOffer(offer: JobOffer) {
  router.push(`/company/edit-offer/${offer.id}`)
}

function deleteOffer(offerId: number) {
  console.log('deleteOffer called with ID:', offerId)
  offerToDelete.value = offerId
  showDeleteModal.value = true
}

function closeDeleteModal() {
  showDeleteModal.value = false
  offerToDelete.value = null
}

async function confirmDelete() {
  if (!offerToDelete.value) return

  deleting.value = true
  
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }

    const response = await fetch(`http://localhost:8000/company/delete-offer/${offerToDelete.value}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error('Erreur lors de la suppression')
    }
    offers.value = offers.value.filter(offer => offer.id !== offerToDelete.value)
    closeDeleteModal()
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Erreur lors de la suppression'
  } finally {
    deleting.value = false
  }
}

function truncateDescription(description: string, maxLength: number = 100): string {
  if (description.length <= maxLength) return description
  return description.substring(0, maxLength) + '...'
}

function formatSalary(salary: number): string {
  return new Intl.NumberFormat('fr-FR', {
    style: 'currency',
    currency: 'EUR',
    minimumFractionDigits: 0
  }).format(salary)
}

function formatDate(dateString: string): string {
  return new Date(dateString).toLocaleDateString('fr-FR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

function getStatusBadgeClass(state: string): string {
  switch (state.toLowerCase()) {
    case 'active':
    case 'actif':
      return 'bg-success'
    case 'inactive':
    case 'inactif':
      return 'bg-secondary'
    case 'draft':
    case 'brouillon':
      return 'bg-warning'
    default:
      return 'bg-primary'
  }
}
</script>

<style scoped>
.card {
  transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
}

.dropdown-item {
  display: flex;
  align-items: center;
}

.dropdown-item:hover {
  background-color: #f8f9fa;
}

.badge {
  font-size: 0.75rem;
}
</style> 