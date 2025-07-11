<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import StudentHeader from '@/components/Students/StudentHeader.vue'
import PersonalInfoForm from '@/components/Students/PersonalInfoForm.vue'
import BioCard from '@/components/Students/BioCard.vue'
import ExperienceCard from '@/components/Students/ExperienceCard.vue'
import EducationCard from '@/components/Students/EducationCard.vue'
import GeneralInfoForm from '@/components/Company/GeneralInfoForm.vue'
import CompanyHeader from '@/components/Company/CompanyHeader.vue'
import IndustryCard from '@/components/Company/IndustryCard.vue'
import DescriptionCard from '@/components/Company/DescriptionCard.vue'
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
      avatar.value = d.photo ?? avatar.value
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
  const res = await fetch('http://localhost:8000/company/profile', {
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

onMounted(fetchCompanyProfile)
</script>

<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />
    <div class="flex-grow-1">
      <PageHeader title="Profile" @toggle-sidebar="showSidebar = true" />
      <div class="py-4 px-3 w-100">
        <CompanyHeader :name="formData.name" :avatar="avatar" />
        <div class="d-flex flex-nowrap gap-4 overflow-auto mt-3">
          <div class="flex-fill" style="min-width: 400px; max-width: 60%">
            <GeneralInfoForm v-model="formData" @submit="submitForm" />
          </div>
          <div class="flex-fill mt-3" style="min-width: 300px; max-width: 30%">
            <CompanyBioCard :bio="description" @update:bio="onBioUpdate" />
            <IndustryCard :industry="industry" @update:industry="(v) => (industry = v)" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
