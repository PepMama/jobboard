<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Créer une offre" @toggle-sidebar="showSidebar = true" />
      <form class="card bg-white shadow-sm mt-3 p-4" @submit.prevent="createOffer(formData)">
        <div class="mb-3">
          <label for="title" class="form-label">Nom de l'offre</label>
          <input type="text" id="title" class="form-control" v-model="formData.title" />
        </div>

        <div class="mb-3">
          <label for="description" class="form-label">Description</label>
          <textarea id="description" class="form-control" v-model="formData.description"></textarea>
        </div>

        <div class="mb-3">
          <label for="state" class="form-label">État</label>
          <input type="text" id="state" class="form-control" v-model="formData.state" />
        </div>

        <div class="mb-3">
          <label for="contractType" class="form-label">Type de contrat</label>
          <select id="contractType" class="form-select" v-model="formData.contractType">
            <option disabled value="">Sélectionnez le type de contrat</option>
            <option value="Stage">Stage</option>
            <option value="Alternance">Alternance</option>
            <option value="CDI">CDI</option>
            <option value="CDD">CDD</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="salary" class="form-label">Salaire</label>
          <input type="number" id="salary" class="form-control" v-model.number="formData.salary" />
        </div>

        <div class="mb-3">
          <label for="city" class="form-label">Ville</label>
          <input type="text" id="city" class="form-control" v-model="formData.city" />
        </div>

        <div class="mb-3 form-check">
          <label class="form-check-label" for="remote">En télétravail ?</label>
          <input type="checkbox" id="remote" class="form-check-input" v-model="formData.remote" />
        </div>

        <div class="mb-3">
          <label for="startDate" class="form-label">Date de début</label>
          <input type="date" id="startDate" class="form-control" v-model="formData.startDate" />
        </div>

        <button type="submit" class="btn btn-outline-primary" style="color: #fff">Créer l'offre</button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'

const formData = ref({
  title: '',
  description: '',
  state: '',
  contractType: '',
  salary: null as number | null,
  city: '',
  remote: false,
  startDate: '',
})

const showSidebar = ref(true)

async function createOffer(offer: typeof formData.value) {
  const t = localStorage.getItem('token')
  if (!t) return

  try {
    const response = await fetch('https://127.0.0.1:8000/company/manage-offer', {
      headers: {
        Authorization: `Bearer ${t}`,
      },
      method: 'POST',
      body: JSON.stringify(offer),
    })
    if (!response.ok) {
      throw new Error("Erreur lors de la création de l'offre")
    }
    const data = await response.json()
    console.log('Offre créée :', data)
    window.location.href = '/company/offers'
  } catch (error) {
    console.error("Erreur lors de la création de l'offre :", error)
  }
}
</script>
