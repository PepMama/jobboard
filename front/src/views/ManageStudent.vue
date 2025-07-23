<!-- src/components/Students/ManageStudent.vue -->
<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar :visible="showSidebar" @close="showSidebar = false" />

    <div class="flex-grow-1 p-4">
      <PageHeader title="Mon profil" @toggle-sidebar="showSidebar = true" />

      <div
        v-if="$route.query.incomplete"
        class="alert alert-warning text-center fw-bold mb-4"
        style="font-size:1.2rem;"
      >
        Veuillez compléter vos informations avant de continuer.
      </div>

      <div class="container-fluid">
        <div class="row g-4">
          <div class="col-12 col-lg-8 d-flex flex-column gap-4">
            <StudentHeader
              :firstname="formData.firstname"
              :name="formData.name"
              :avatar="avatar"
              :phone="formData.phone"
              :age="formData.age"
              :address="formData.address"
              :city="formData.city"
              :postalCode="formData.postalCode"
              :bio="bio"
              @update:bio="onBioUpdate"
              @edit="showModal = true"
              @photo-updated="onPhotoUpdate"
            />

            <div class="row g-4">
              <div class="col-12 col-lg-6">
                <EducationCard
                  :educations="educations"
                  @changed="fetchStudentProfile"
                />
              </div>
              <div class="col-12 col-lg-6">
                <ExperienceCard
                  :experiences="experiences"
                  @changed="fetchStudentProfile"
                />
              </div>
            </div>

            <StudentCompetencies />
          </div>

          <div class="col-12 col-lg-4">
            <UploadCvAndPortfolio
              :cv="formData.cv"
              :github="formData.github"
              :linkedin="formData.linkedin"
              @update:cv="formData.cv = $event"
              @update:github="formData.github = $event"
              @update:linkedin="formData.linkedin = $event"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de modification des infos perso -->
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
            <h5 class="modal-title">Modifier mes informations</h5>
            <button
              type="button"
              class="btn-close"
              @click="showModal = false"
            />
          </div>
          <div class="modal-body">
          <PersonalInfoForm
            :model-value="formData"
            :is-public="false"
            :key="Number(showModal)"
            @submit="handlePersonalInfoSubmit"
          />
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

<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import StudentHeader from '@/components/Students/StudentHeader.vue'
import PersonalInfoForm from '@/components/Students/PersonalInfoForm.vue'
import ExperienceCard from '@/components/Students/ExperienceCard.vue'
import EducationCard from '@/components/Students/EducationCard.vue'
import UploadCvAndPortfolio from '@/components/Students/UploadCvAndPortfolioCard.vue'
import StudentCompetencies from '@/components/Students/StudentCompetencies.vue'

const route = useRoute()
const showSidebar = ref(true)
const showModal = ref(false)

const avatar = ref('https://via.placeholder.com/80')
const bio = ref<string>('')
const experiences = ref<any[]>([])
const educations = ref<any[]>([])

const formData = reactive({
  firstname: '',
  name: '',
  phone: '',
  age: '',
  address: '',
  city: '',
  postalCode: '',
  cv: '',
  github: '',
  linkedin: ''
})

async function fetchStudentProfile() {
  const token = localStorage.getItem('token')
  if (!token) return

  const resp = await fetch(
    `${import.meta.env.VITE_API_URL}/student/profile`,
    { headers: { Authorization: `Bearer ${token}` } }
  )
  if (resp.ok && resp.status !== 204) {
    const data = await resp.json()
    Object.assign(formData, {
      firstname: data.firstname ?? '',
      name: data.name ?? '',
      phone: data.phone_number ?? '',
      age: data.age ?? '',
      address: data.address ?? '',
      city: data.city ?? '',
      postalCode: data.postal_code ?? ''
    })
    bio.value = data.description ?? ''
    avatar.value = data.photo ?? avatar.value
  }

  // expériences
  const exp = await fetch(
    `${import.meta.env.VITE_API_URL}/student/experiences`,
    { headers: { Authorization: `Bearer ${token}` } }
  )
  experiences.value = exp.ok ? await exp.json() : []

  // éducation
  const eduRes = await fetch(
    `${import.meta.env.VITE_API_URL}/student/educations`,
    { headers: { Authorization: `Bearer ${token}` } }
  )
  educations.value = eduRes.ok ? await eduRes.json() : []
}

onMounted(fetchStudentProfile)

async function handlePersonalInfoSubmit(payload: Record<string, any>) {
  const token = localStorage.getItem('token')
  if (!token) return

  const body = {
    firstname: payload.firstname,
    name: payload.name,
    phone_number: payload.phone,
    age: payload.age !== '' ? Number(payload.age) : null,
    address: payload.address,
    city: payload.city,
    postal_code: payload.postalCode,
    description: bio.value,
    photo: null,
    linkedin: payload.linkedin,
    github: payload.github,
    cv: payload.cv
  }

  const res = await fetch(
    `${import.meta.env.VITE_API_URL}/student/manage-profile`,
    {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`
      },
      body: JSON.stringify(body)
    }
  )
  if (res.ok) {
    alert('Profil mis à jour')
    await fetchStudentProfile()
  } else {
    console.error('Erreur update:', await res.text())
    alert('Échec mise à jour')
  }
}

function onBioUpdate(newBio: string) {
  bio.value = newBio
  handlePersonalInfoSubmit({ ...formData })
}

function onPhotoUpdate(newPhoto: string) {
  avatar.value = newPhoto
}
</script>

<style scoped>
.dashboard-bg {
  background: #f7faff;
}
.card {
  border-radius: 18px !important;
  box-shadow: 0 2px 16px 0 #e3e8f7 !important;
  border: none !important;
}
.btn-outline-primary {
  color: #5651ab;
  border-color: #5651ab;
  transition: 0.3s;
}
.btn-outline-primary:hover {
  background: #5651ab;
  color: #fff;
  border-color: #5651ab;
}
.flex-grow-1 {
  overflow-y: auto;
  max-height: 100vh;
}
@media (max-width: 991px) {
  .container-fluid .row > div {
    margin-bottom: 1.5rem;
  }
}
</style>
