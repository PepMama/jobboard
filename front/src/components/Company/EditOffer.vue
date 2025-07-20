<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Modifier l'offre" @toggle-sidebar="showSidebar = true" />
      
      <!-- Loading state -->
      <div v-if="loading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Chargement...</span>
        </div>
        <p class="mt-3 text-muted">Chargement de l'offre...</p>
      </div>

      <!-- Error state -->
      <div v-else-if="error" class="alert alert-danger" role="alert">
        <h5 class="alert-heading">Erreur</h5>
        <p>{{ error }}</p>
        <button @click="loadOffer" class="btn btn-outline-danger btn-sm">
          Réessayer
        </button>
      </div>

      <!-- Form -->
      <div v-else class="card bg-white shadow-sm mt-3 p-4">
        <form @submit.prevent="updateOffer">
          <div class="mb-3">
            <label for="title" class="form-label">Nom de l'offre</label>
            <input 
              type="text" 
              id="title" 
              class="form-control" 
              v-model="formData.title" 
              required
            />
          </div>

          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea 
              id="description" 
              class="form-control" 
              v-model="formData.description"
              rows="4"
              required
            ></textarea>
          </div>

          <div class="mb-3">
            <label for="state" class="form-label">État</label>
            <select id="state" class="form-select" v-model="formData.state" required>
              <option value="">Sélectionnez l'état</option>
              <option value="visible">Visible</option>
              <option value="non_visible">Non visible</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="contractType" class="form-label">Type de contrat</label>
            <select id="contractType" class="form-select" v-model="formData.contractType" required>
              <option value="">Sélectionnez le type de contrat</option>
              <option value="Stage">Stage</option>
              <option value="Alternance">Alternance</option>
            </select>
          </div>

          <div class="mb-3">
            <label for="salary" class="form-label">Salaire (€)</label>
            <input 
              type="number" 
              id="salary" 
              class="form-control" 
              v-model.number="formData.salary"
              min="0"
            />
          </div>

          <div class="mb-3">
            <label for="city" class="form-label">Ville</label>
            <input 
              type="text" 
              id="city" 
              class="form-control" 
              v-model="formData.city"
              required
            />
          </div>

          <div class="mb-3 form-check">
            <input 
              type="checkbox" 
              id="remote" 
              class="form-check-input" 
              v-model="formData.remote"
            />
            <label class="form-check-label" for="remote">
              En télétravail ?
            </label>
          </div>

          <div class="mb-3">
            <label for="startDate" class="form-label">Date de début</label>
            <input 
              type="date" 
              id="startDate" 
              class="form-control" 
              v-model="formData.startDate"
              required
            />
          </div>

          <div class="d-flex gap-3">
            <button 
              type="submit" 
              class="btn btn-primary" 
              :disabled="saving"
            >
              <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
              {{ saving ? 'Modification...' : 'Modifier l\'offre' }}
            </button>
            <button 
              type="button" 
              class="btn btn-outline-secondary" 
              @click="goBack"
            >
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
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
const route = useRoute()
const showSidebar = ref(true)
const loading = ref(true)
const saving = ref(false)
const error = ref<string | null>(null)

const formData = ref({
  id: null as number | null,
  title: '',
  description: '',
  state: '',
  contractType: '',
  salary: null as number | null,
  city: '',
  remote: false,
  startDate: '',
})

onMounted(() => {
  loadOffer()
})

async function loadOffer() {
  loading.value = true
  error.value = null
  
  try {
    const offerId = route.params.id
    if (!offerId) {
      throw new Error('ID de l\'offre manquant')
    }

    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }

    const response = await fetch(`https://localhost:8000/company/offer/${offerId}`, {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      }
    })

    if (!response.ok) {
      throw new Error('Erreur lors du chargement de l\'offre')
    }

    const data = await response.json()
    
    // Remplir le formulaire avec les données de l'offre
    formData.value = {
      id: data.id,
      title: data.title,
      description: data.description,
      state: data.state,
      contractType: data.contractType,
      salary: data.salary,
      city: data.city,
      remote: data.remote,
      startDate: data.startDate,
    }
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Une erreur est survenue'
  } finally {
    loading.value = false
  }
}

async function updateOffer() {
  saving.value = true
  
  try {
    const token = localStorage.getItem('token')
    if (!token) {
      throw new Error('Token non trouvé')
    }

    const response = await fetch('https://localhost:8000/company/manage-offer', {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(formData.value)
    })

    if (!response.ok) {
      const errorData = await response.json()
      throw new Error(errorData.error || 'Erreur lors de la modification')
    }

    // Redirection vers la liste des offres
    router.push('/company/offers')
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Erreur lors de la modification'
  } finally {
    saving.value = false
  }
}

function goBack() {
  router.push('/company/offers')
}
</script>

<style scoped>
.form-control:focus,
.form-select:focus {
  border-color: #5651ab;
  box-shadow: 0 0 0 0.2rem rgba(86, 81, 171, 0.25);
}

.btn-primary {
  background-color: #5651ab;
  border-color: #5651ab;
}

.btn-primary:hover {
  background-color: #4a4580;
  border-color: #4a4580;
}
</style> 