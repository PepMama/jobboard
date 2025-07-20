<template>
  <div class="card bg-white shadow-sm mt-3 p-4">
    <div class="d-flex flex-row flex-wrap align-items-center gap-4">
      <div class="flex-shrink-0 position-relative">
        <img
          :src="avatarUrl"
          alt="Logo"
          class="rounded-circle border"
          width="120"
          height="120"
          style="object-fit: cover"
        />
        <button
          class="btn btn-sm btn-light position-absolute bottom-0 end-0 border shadow"
          style="transform: translate(25%, 25%);"
          @click="triggerFileInput"
          title="Changer le logo"
        >
          <Pencil :size="18" />
        </button>
        <input
          ref="fileInput"
          type="file"
          accept="image/*"
          class="d-none"
          @change="onFileChange"
        />
      </div>
      <div>
        <h4 class="fw-bold mb-3">{{ name }}</h4>
        <ul class="list-unstyled text-secondary small mb-3">
          <li v-if="website"><strong>Site web :</strong> {{ website }}</li>
          <li v-if="city">
            <strong>Ville :</strong> {{ city }} <span v-if="postalCode">({{ postalCode }})</span>
          </li>
          <li v-if="address"><strong>Adresse :</strong> {{ address }}</li>
          <li v-if="siret"><strong>SIRET :</strong> {{ siret }}</li>
          <li v-if="linkedin"><strong>LinkedIn :</strong> {{ linkedin }}</li>
          <li v-if="phone"><strong>Téléphone :</strong> {{ phone }}</li>
        </ul>

        <button class="btn btn-outline-primary" @click="$emit('edit')">
          <i class="bi bi-pencil me-2"></i> Modifier mes infos
        </button>
      </div>
    </div>
    <div class="mt-4">
      <label class="fw-bold">Description</label>
      <div v-if="edit">
        <textarea v-model="localBio" class="form-control"></textarea>
      </div>
      <p class="fs-6" v-else>{{ bio || 'Aucune description.' }}</p>

      <button class="btn btn-outline-primary mt-2" @click="toggleEdit">
        {{ edit ? 'Sauvegarder' : 'Modifier la description' }}
      </button>
    </div>
    <div v-if="uploadError" class="alert alert-danger mt-3 py-2 px-3">
      {{ uploadError }}
    </div>
    <div v-if="uploading" class="text-primary small mt-2">Envoi du logo...</div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'
import { Pencil } from 'lucide-vue-next'

const props = defineProps<{
  name?: string
  avatar: string
  phone?: string
  website?: string
  linkedin?: string
  address?: string
  city?: string
  postalCode?: string
  siret?: string
  bio: string
}>()

const emit = defineEmits(['update:bio', 'edit', 'logo-updated'])

const edit = ref(false)
const localBio = ref(props.bio)

watch(
  () => props.bio,
  (v) => {
    if (!edit.value) localBio.value = v
  },
)

watch(
  () => props.avatar,
  (v) => {
    avatarUrl.value = v
  },
)

function toggleEdit() {
  if (edit.value) emit('update:bio', localBio.value)
  edit.value = !edit.value
}

const avatarUrl = ref(props.avatar)
const fileInput = ref<HTMLInputElement | null>(null)
const uploading = ref(false)
const uploadError = ref<string | null>(null)

function triggerFileInput() {
  uploadError.value = null
  fileInput.value?.click()
}

async function onFileChange(e: Event) {
  uploadError.value = null
  const files = (e.target as HTMLInputElement).files
  if (!files || !files[0]) return
  const file = files[0]

  // Validation côté front
  if (!file.type.startsWith('image/')) {
    uploadError.value = 'Le fichier doit être une image.'
    return
  }
  if (file.size > 5 * 1024 * 1024) {
    uploadError.value = 'Image trop volumineuse (max 5 Mo)'
    return
  }

  uploading.value = true
  try {
    const formData = new FormData()
    formData.append('logo', file)
    const token = localStorage.getItem('token')
    const response = await fetch('https://127.0.0.1:8000/company/upload-logo', {
      method: 'POST',
      headers: {
        Authorization: `Bearer ${token}`
      },
      body: formData
    })
    const data = await response.json()
    if (!response.ok) {
      uploadError.value = data.error || 'Erreur lors de l\'upload.'
    } else {
      avatarUrl.value = data.logo_url
      emit('logo-updated', data.logo_url)
    }
  } catch (err) {
    uploadError.value = 'Erreur lors de l\'upload.'
  } finally {
    uploading.value = false
  }
}
</script>

<style scoped>
.position-relative { 
  position: relative; 
}

.position-absolute { 
  bottom: 0; 
}

.end-0 { 
  right: 0; 
}

.border 
{ 
  border: 2px solid #e5e5e5; 
}
</style>
