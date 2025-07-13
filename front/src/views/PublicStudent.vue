<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import StudentHeader from '@/components/Students/StudentHeader.vue'
import PersonalInfoForm from '@/components/Students/PersonalInfoForm.vue'
import ExperienceCard from '@/components/Students/ExperienceCard.vue'
import EducationCard from '@/components/Students/EducationCard.vue'
import UploadCvAndPortfolio from '@/components/Students/UploadCvAndPortfolioCard.vue'
import { useRoute } from 'vue-router'

const avatar = ref('https://via.placeholder.com/80')

const route = useRoute()
const name = route.params.name as string

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
  linkedin: '',
  bio: '',
  avatar: '',
})
const showModal = ref(false)

async function fetchStudentProfile() {
  const t = localStorage.getItem('token')
  if (!t) return

  const prof = await fetch(`https://localhost:8000/company/${encodeURIComponent(name)}`, {
    headers: { Authorization: `Bearer ${t}` },
  })

  if (prof.ok && prof.status !== 204) {
    const d = await prof.json()
    formData.firstname = d.firstname ?? ''
    formData.name = d.name ?? ''
    formData.phone = d.phone_number ?? ''
    formData.age = d.age ?? ''
    formData.address = d.address ?? ''
    formData.city = d.city ?? ''
    formData.postalCode = d.postal_code ?? ''
    formData.cv = d.cv ?? ''
    formData.github = d.github ?? ''
    formData.linkedin = d.linkedin ?? ''
    formData.bio = d.description ?? ''
    formData.avatar = d.photo ?? ''
  }

  //   const exp = await fetch('http://localhost:8000/student/experiences', {
  //     headers: { Authorization: `Bearer ${t}` }
  //   })
  //   experiences.value = exp.ok ? await exp.json() : []

  //   const eduRes = await fetch('http://localhost:8000/student/educations', {
  //     headers: { Authorization: `Bearer ${t}` }
  //   })
  //   educations.value = eduRes.ok ? await eduRes.json() : []
}

async function submitForm() {
  const t = localStorage.getItem('token')
  if (!t) return
  const payload = {
    firstname: formData.firstname,
    name: formData.name,
    phone_number: formData.phone,
    age: formData.age,
    address: formData.address,
    city: formData.city,
    postal_code: formData.postalCode,
    description: formData.bio,
    avatar: formData.avatar, // Assuming you handle photo upload separately
    photo: null,
    linkedin: formData.linkedin,
    github: formData.github,
    cv: formData.cv,
  }
  const res = await fetch('https://localhost:8000/student/manage-profile', {
    method: 'PUT',
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${t}`,
    },
    body: JSON.stringify(payload),
  })
  if (res.ok) alert('Profil mis à jour')
}

onMounted(fetchStudentProfile)
</script>

<template>
  <div class="d-flex w-100 min-vh-100 dashboard-bg">
    <Sidebar />
    <div class="flex-grow-1 p-4">
      <PageHeader title="Profile" />

      <div class="container-fluid">
        <div class="row g-4">
          <!-- Partie gauche : Header + formations/expériences -->
          <div class="col-12 col-lg-8 d-flex flex-column gap-4">
            <!-- Bloc infos -->
            <StudentHeader
              :firstname="formData.firstname"
              :name="formData.name"
              :avatar="avatar"
              :phone="formData.phone"
              :age="formData.age"
              :address="formData.address"
              :city="formData.city"
              :postalCode="formData.postalCode"
              :bio="formData.bio"
              @edit="showModal = false"
            />

            <!-- Bloc formations + expériences -->
            <div class="row g-4">
              <div class="col-12 col-lg-6">
                <!-- <EducationCard :educations="educations" @changed="fetchStudentProfile" /> -->
              </div>
              <div class="col-12 col-lg-6">
                <!-- <ExperienceCard :experiences="experiences" @changed="fetchStudentProfile" /> -->
              </div>
            </div>
          </div>

          <!-- Partie droite : Upload fichiers -->
          <div class="col-12 col-lg-4">
            <!-- <UploadCvAndPortfolio
              :cv="formData.cv"
              :github="formData.github"
              :linkedin="formData.linkedin"
            /> -->
          </div>
        </div>
      </div>
    </div>

    <!-- Modale -->
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
            <button type="button" class="btn-close" @click="showModal = false"></button>
          </div>
          <div class="modal-body">
            <PersonalInfoForm v-model="formData" :is-public="true" />
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
