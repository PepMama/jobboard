<template>
  <div class="card bg-white shadow-sm mt-3">
    <div class="card-body">
      <h5>Ajouter des fichiers</h5>
      <label class="block font-bold">CV (PDF uniquement)</label>
      <input type="file" accept="application/pdf" @change="handleCvUpload" />

      <div v-if="cvUrl" class="mt-2">
        <embed :src="cvUrl" type="application/pdf" width="100%" height="300px" />
        <button class="mt-2 px-4 py-1 bg-red-500 text-white rounded" @click="deleteCv">
          Supprimer le CV
        </button>
      </div>
    </div>

    <div class="card-body">
      <label class="block font-bold">Lien GitHub</label>
      <input
        v-model="githubUrl"
        type="text"
        class="w-full border p-2 rounded"
        placeholder="http://github.com/username"
      />
      <button class="btn btn-outline-primary mt-2" @click="updateGithub">Ajouter/modifier</button>
    </div>

    <div class="card-body">
      <label class="block font-bold">Lien Linkedin</label>
      <input
        v-model="linkedinUrl"
        type="text"
        class="w-full border p-2 rounded"
        placeholder="http://linkedin.com/in/username"
      />
      <button class="btn btn-outline-primary mt-2" @click="updateLinkLinkedin">
        Ajouter/modifier
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'

const props = defineProps({
  cv: String,
  github: String,
  linkedin: String,
})

const emit = defineEmits(['update:cv', 'update:github', 'update:linkedin'])

const cvUrl = ref(props.cv || null)
const githubUrl = ref(props.github || '')
const linkedinUrl = ref(props.linkedin || '')

watch(
  () => props.cv,
  (val) => (cvUrl.value = val),
)
watch(
  () => props.github,
  (val) => (githubUrl.value = val),
)
watch(
  () => props.linkedin,
  (val) => (linkedinUrl.value = val),
)


async function handleCvUpload(e) {
  const file = e.target.files[0]
  if (!file || file.type !== 'application/pdf') {
    alert('Seuls les fichiers PDF sont autorisés')
    return
  }

  const formData = new FormData()
  formData.append('cv', file)

  try {
    const res = await fetch('https://localhost:8000/student/upload-cv', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('token')}`,
        Accept: 'application/json', 
      },
      body: formData,
    })

    const responseText = await res.text()

    let data
    try {
      data = JSON.parse(responseText)
    } catch (err) {
      throw new Error('Réponse non valide : le serveur a renvoyé autre chose que du JSON.')
    }

    if (!res.ok) {
      throw new Error(data.error || 'Erreur serveur inconnue')
    }

    cvUrl.value = `/uploads/cvs/${filename}` 
    emit('update:cv', data.cv)
  } catch (error) {
    console.error('Erreur lors de l’envoi :', error)
    alert(error.message || 'Erreur réseau')
  }
}

async function deleteCv() {
  const res = await fetch('https://localhost:8000/student/delete-cv', {
    method: 'DELETE',
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
    },
  })

  const data = await res.json()
  if (res.ok) {
    cvUrl.value = null
    emit('update:cv', null)
  } else {
    alert(data.error)
  }
}

async function updateLinkLinkedin() {
  const res = await fetch('https://localhost:8000/student/update-linkedin', {
    method: 'PUT',
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ linkedin: linkedinUrl.value }),
  })

  const data = await res.json()
  if (res.ok) {
    emit('update:linkedin', data.linkedin)
  } else {
    alert(data.error)
  }
}

async function updateGithub() {
  const res = await fetch('https://localhost:8000/student/update-github', {
    method: 'PUT',
    headers: {
      Authorization: `Bearer ${localStorage.getItem('token')}`,
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({ github: githubUrl.value }),
  })

  const data = await res.json()
  if (res.ok) {
    emit('update:github', data.github)
  } else {
    alert(data.error)
  }
}
</script>

<style src="@/CSS/global.css"></style>
