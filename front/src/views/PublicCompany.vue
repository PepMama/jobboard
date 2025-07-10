<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

import Sidebar from '@/components/Global/NavBar.vue'
import CompanyHeader from '@/components/Company/CompanyHeader.vue'
import CompanyBioCard from '@/components/Company/CompanyBioCard.vue'
import IndustryCard from '@/components/Company/IndustryCard.vue'
import GeneralInfoForm from '@/components/Company/GeneralInfoForm.vue'
import Avatar from '@/assets/avatar-defaut.jpg'

const route = useRoute()
const name = route.params.name as string
console.log('Company name:', name)


const data = reactive({
  name: '',
  phone: '',
  website: '',
  linkedin: '',
  address: '',
  city: '',
  postal_code: '',
  description: '',
  industry: '',
  avatar: '',
})

async function fetchCompanyProfile() {
  const t = localStorage.getItem('token')
  if (!t) return

  try {
    const res = await fetch(`http://localhost:8000/company/${encodeURIComponent(name)}`, {
      headers: { Authorization: `Bearer ${t}` },
    })
    if (!res.ok) throw new Error('Entreprise non trouvée')
    const d = await res.json()

    data.name = d.name ?? ''
    data.phone = d.phone_number ?? ''
    data.website = d.website ?? ''
    data.linkedin = d.linkedin ?? ''
    data.address = d.address ?? ''
    data.city = d.city ?? ''
    data.postal_code = d.postal_code ?? ''
    data.description = d.description ?? ''
    data.industry = d.industry ?? ''
    data.avatar = d.photo ?? ''
  } catch (e) {
    console.error(e)
  }
}

onMounted(fetchCompanyProfile)
</script>

<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar />
    <div class="flex-grow-1 p-4">
  <div class="d-flex align-items-center mb-3">
    <img v-if="data.avatar" :src="data.avatar" alt="Company Avatar"
      class="img-fluid rounded-circle me-3"
      style="width: 80px; height: 80px;">
    <img v-else :src="Avatar" alt="Default Company Avatar"
      class="img-fluid rounded-circle me-3"
      style="width: 80px; height: 80px;">
    
    <h1 class="mb-0">{{ data.name || 'Nom de l\'entreprise inconnu' }}</h1>
  </div>
      <hr>
      <div>
        <h2>À propos de la compagnie</h2>
        <p v-if="data.description">{{ data.description || 'L\'entreprise n\'a pas encore de description.' }}</p>
        <p v-else>L'entreprise n'a pas encore de description.</p>
        <hr>
        <div class="d-flex justify-content-between mb-2">
          <strong>Téléphone</strong>
          <span>{{ data.phone || 'Non renseigné' }}</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-2">
          <strong>Site web</strong>
          <span v-if="data.website"><a :href="data.website" target="_blank">{{ data.website || 'Non renseigné'
              }}</a></span>
          <span v-else>Non renseigné</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-2">
          <strong>LinkedIn</strong>
          <span v-if="data.linkedin"><a :href="data.linkedin" target="_blank">{{ data.linkedin || 'Non renseigné'
              }}</a></span>
          <span v-else>Non renseigné</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-2">
          <strong>Adresse</strong>
          <span>{{ data.address || 'Non renseigné' }}</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-2">
          <strong>Ville</strong>
          <span>{{ data.city || 'Non renseigné' }}</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-2">
          <strong>Code postal</strong>
          <span>{{ data.postal_code || 'Non renseigné' }}</span>
        </div>
      </div>
    </div>
  </div>
</template>
