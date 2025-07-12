<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import GeneralInfoForm from '@/components/Company/GeneralInfoForm.vue'
import CompanyHeader from '@/components/Company/CompanyHeader.vue'
import IndustryCard from '@/components/Company/IndustryCard.vue'
import CompanyBioCard from '@/components/Company/CompanyBioCard.vue'
import PageHeader from '@/components/Global/PageHeader.vue'

const avatar = ref('http://via.placeholder.com/80')

const formData = reactive({
  name: '',
  phone: '',
  website: '',
  linkedin: '',
  address: '',
  city: '',
  postal_code: '',
})

const description = ref('')
const industry = ref('')
const showSidebar = ref(true)
const showModal = ref(false)

async function fetchCompanyProfile() {
  const t = localStorage.getItem('token')
  if (!t) return

  try {
    const prof = await fetch('http://localhost:8000/company/profile', {
      headers: { Authorization: `Bearer ${t}` },
    })

    if (prof.ok && prof.status !== 204) {
      const d = await prof.json()
      formData.name = d.name ?? ''
      formData.phone = d.phone_number ?? ''
      formData.website = d.website ?? ''
      formData.linkedin = d.linkedin ?? ''
      formData.address = d.address ?? ''
      formData.city = d.city ?? ''
      formData.postal_code = d.postal_code ?? ''
      industry.value = d.industry ?? ''
      description.value = d.description ?? ''
      avatar.value = d.logo ?? avatar.value
    }
  } catch (error) {
    console.error("Erreur lors de la récupération du profil de l'entreprise :", error)
  }
}

async function submitForm() {
  const t = localStorage.getItem('token')
  if (!t) return
  const payload = {
    name: formData.name,
    phone_number: formData.phone,
    website: formData.website,
    linkedin: formData.linkedin,
    logo: null,
    industry: industry.value,
    city: formData.city,
    address: formData.address,
    postal_code: formData.postal_code,
    description: description.value,
  }

  console.log('Envoi des données du formulaire:', payload);
  const res = await fetch('https://localhost:8000/company/profile', {
    method: 'PUT',
    headers: { 'Content-Type': 'application/json', Authorization: `Bearer ${t}` },
    body: JSON.stringify(payload),
  })
  if (res.ok) alert('Profil mis à jour')
}

function onBioUpdate(newBio: string) {
  description.value = newBio
  submitForm()
}

function onIndustryUpdate(newIndustry: string) {
  industry.value = newIndustry
  submitForm() // Ajoute cette ligne pour sauvegarder automatiquement
}

function onLogoUpdate(newLogo: string) {
  avatar.value = newLogo
}

function handleModalSubmit() {
  submitForm()
  showModal.value = false
}

onMounted(fetchCompanyProfile)
</script>

<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Profile" @toggle-sidebar="showSidebar = true" />

      <div class="container-fluid">
        <div class="row g-4">
          <div class="col-12 col-lg-6">
            <CompanyHeader
              :name="formData.name"
              :avatar="avatar"
              :phone="formData.phone"
              :website="formData.website"
              :linkedin="formData.linkedin"
              :address="formData.address"
              :city="formData.city"
              :postalCode="formData.postal_code"
              :bio="description"
              @update:bio="onBioUpdate"
              @edit="showModal = true"
              @logo-updated="onLogoUpdate"
            />
          </div>
          <div class="col-12 col-lg-6">
            <IndustryCard :industry="industry" @update:industry="onIndustryUpdate" />
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      :class="{ show: showModal }"
      tabindex="-1"
      v-show="showModal"
      style="display: block; z-index: 1055"
    >
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Modifier les informations</h5>
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <GeneralInfoForm v-model="formData" @submit="handleModalSubmit" />
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal-backdrop fade"
      :class="{ show: showModal }"
      v-if="showModal"
      style="z-index: 1050"
      @click="showModal = false"
    />
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
