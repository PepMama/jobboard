<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'
import StudentHeader from '../components/Students/StudentHeader.vue'
import PersonalInfoForm from '../components/Students/PersonalInfoForm.vue'

const avatar = ref('https://search.brave.com/images?q=troll')

const formData = reactive({
  firstname: '',
  name: '',
  phone: '',
  age: '',
  address: '',
  city: '',
  postalCode: '',
  description: ''
})

async function fetchStudentProfile () {
  const token = localStorage.getItem('token')
  if (!token) return

  try {
    const res = await fetch('http://localhost:8000/student/profile', {
      headers: { Authorization: `Bearer ${token}` }
    })

    if (res.status === 204) return

    const d = await res.json()
    if (!res.ok) throw new Error(d.error)

    // remplissage
    formData.firstname = d.firstname ?? ''
    formData.name = d.name ?? ''
    formData.phone = d.phone_number ?? ''
    formData.age = d.age ?? ''
    formData.address = d.address ?? ''
    formData.city = d.city ?? ''
    formData.postalCode = d.postal_code ?? ''
    formData.description = d.description ?? ''
    avatar.value = d.photo ?? avatar.value
  } catch (err) {
    console.error('[fetchStudentProfile]', err)
  }
}

async function submitForm () {
  const token = localStorage.getItem('token')
  if (!token) return

  const payload = {
    firstname: formData.firstname,
    name: formData.name,
    phone_number: formData.phone,
    age: formData.age,
    address: formData.address,
    city: formData.city,
    postal_code: formData.postalCode,
    description: formData.description,
    photo: avatar.value === 'https://via.placeholder.com/80' ? null : avatar.value,
    linkedin: null,
    github: null,
    cv: null
  }

  try {
    const res = await fetch('http://localhost:8000/student/profile', {
      method:  'PUT',
      headers: {
        'Content-Type': 'application/json',
        Authorization:  `Bearer ${token}`
      },
      body: JSON.stringify(payload)
    })

    const d = await res.json()
    if (!res.ok) throw new Error(d.error)

    alert('Profil mis à jour avec succès !')
  } catch (err) {
    console.error('[submitForm]', err)
  }
}
onMounted(fetchStudentProfile)
</script>

<template>
  <div class="d-flex w-100 min-vh-100">
    <Sidebar />

    <div class="flex-grow-1">
      <div class="py-4 px-3 w-100">
        <StudentHeader
          :firstname="formData.firstname"
          :name="formData.name"
          :avatar="avatar"
        />

        <div class="d-flex flex-nowrap gap-4 overflow-auto mt-3">
          <div class="flex-fill" style="min-width: 400px; max-width: 60%;">
            <PersonalInfoForm
              v-model="formData"
              @submit="submitForm"
            />
          </div>

          <div class="flex-fill" style="min-width: 300px; max-width: 30%;">

          </div>
        </div>
      </div>
    </div>
  </div>
</template>
