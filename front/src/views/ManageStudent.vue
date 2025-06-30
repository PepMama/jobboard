<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import Sidebar          from '@/components/Global/NavBar.vue'
import StudentHeader    from '@/components/Students/StudentHeader.vue'
import PersonalInfoForm from '@/components/Students/PersonalInfoForm.vue'
import BioCard          from '@/components/Students/BioCard.vue'
import ExperienceCard   from '@/components/Students/ExperienceCard.vue'
import EducationCard from '@/components/Students/EducationCard.vue'
import UploadCvAndPortfolio from '@/components/Students/UploadCvAndPortfolioCard.vue'

const avatar = ref('https://via.placeholder.com/80')

const formData = reactive({
  firstname:'', name:'', phone:'', age:'', address:'', city:'', postalCode:'', cv: '',
  github: ''

})

const bio = ref('')
const experiences = ref<any[]>([])
const educations = ref<any[]>([])

async function fetchStudentProfile(){
  const t = localStorage.getItem('token')
  if(!t) return

  const prof = await fetch('https://localhost:8000/student/profile',{
    headers:{Authorization:`Bearer ${t}`}
  })

  if(prof.ok && prof.status!==204){
    const d = await prof.json()
    formData.firstname=d.firstname??''
    formData.name = d.name??''
    formData.phone = d.phone_number??''
    formData.age = d.age??''
    formData.address =d.address??''
    formData.city = d.city??''
    formData.postalCode = d.postal_code??''
    formData.cv = d.cv ?? ''
    formData.github = d.github ?? ''
    bio.value = d.description??''
    avatar.value = d.photo??avatar.value
  }

  const exp = await fetch('https://localhost:8000/student/experiences',{
    headers:{Authorization:`Bearer ${t}`}
  })
  experiences.value = exp.ok ? await exp.json() : []

  const eduRes = await fetch('https://localhost:8000/student/educations',{headers:{
    Authorization:`Bearer ${t}`}
  })
  educations.value = eduRes.ok ? await eduRes.json() : []
}

async function submitForm(){
  const t = localStorage.getItem('token')
  if(!t) return
  const payload={
    firstname:formData.firstname,name:formData.name,
    phone_number:formData.phone,age:formData.age,address:formData.address,
    city:formData.city,postal_code:formData.postalCode,description:bio.value,
    photo:null,linkedin:null,github: formData.github,
    cv: formData.cv
  }
  const res = await fetch('https://localhost:8000/student/profile',{
    method:'PUT',headers:{'Content-Type':'application/json',Authorization:`Bearer ${t}`},
    body:JSON.stringify(payload)
  })
  if(res.ok) alert('Profil mis à jour')
}
onMounted(fetchStudentProfile)

function updateCv(newCv: string) {
  formData.cv = newCv
}

function updateGithub(newGithub: string) {
  formData.github = newGithub
}
</script>

<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar/>
    <div class="flex-grow-1">
      <div class="py-4 px-3 w-100">
        <StudentHeader :firstname="formData.firstname" :name="formData.name" :avatar="avatar"/>
        <div class="d-flex flex-nowrap gap-4 overflow-auto mt-3">
          <div class="flex-fill" style="min-width:400px;max-width:60%">
            <PersonalInfoForm v-model="formData" @submit="submitForm"/>
          </div>
          
          <div class="flex-fill mt-3" style="min-width:300px;max-width:30%">
            <BioCard :bio="bio" @update:bio="v=>bio=v"/>
            <EducationCard :educations="educations" @changed="fetchStudentProfile"/>
            <ExperienceCard :experiences="experiences" @changed="fetchStudentProfile"/>
            <UploadCvAndPortfolio :cv="formData.cv" :github="formData.github" @update:cv="updateCv" @update:github="updateGithub"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
