<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

import Sidebar from '@/components/Global/NavBar.vue'
import CompanyHeader from '@/components/Company/CompanyHeader.vue'
import CompanyBioCard from '@/components/Company/CompanyBioCard.vue'
import IndustryCard from '@/components/Company/IndustryCard.vue'
import GeneralInfoForm from '@/components/Company/GeneralInfoForm.vue'

const route = useRoute()
const name = route.params.name as string
console.log('Company name:', name)

const avatar = ref('http://via.placeholder.com/80')

const formData = reactive({
  name: '',
  phone: '',
  website: '',
  linkedin: '',
  address: '',
  city: '',
  postal_code: '',
  description: '',
  industry: '',
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

    formData.name = d.name ?? ''
    formData.phone = d.phone_number ?? ''
    formData.website = d.website ?? ''
    formData.linkedin = d.linkedin ?? ''
    formData.address = d.address ?? ''
    formData.city = d.city ?? ''
    formData.postal_code = d.postal_code ?? ''
    formData.description = d.description ?? ''
    formData.industry = d.industry ?? ''
    avatar.value = d.photo ?? avatar.value
  } catch (e) {
    console.error(e)
  }
}

onMounted(fetchCompanyProfile)
</script>

<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar />
    <div class="flex-grow-1">
      <div class="py-4 px-3 w-100">
        <CompanyHeader :name="formData.name" :avatar="avatar" />
        <div class="d-flex flex-nowrap gap-4 overflow-auto mt-3">
          <div class="flex-fill" style="min-width: 400px; max-width: 60%">
            <GeneralInfoForm v-model="formData" :readonly="true" />
          </div>
          <div class="flex-fill mt-3" style="min-width: 300px; max-width: 30%">
            <CompanyBioCard :bio="formData.description" :readonly="true" />
            <IndustryCard :industry="formData.industry" :readonly="true" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
