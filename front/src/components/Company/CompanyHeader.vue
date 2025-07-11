<template>
  <div class="card bg-white shadow-sm mt-3 p-4">
    <div class="d-flex flex-row flex-wrap align-items-center gap-4">
      <div class="flex-shrink-0">
        <img
          :src="avatar"
          alt="Logo"
          class="rounded-circle"
          width="120"
          height="120"
          style="object-fit: cover"
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
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  name?: string
  avatar: string
  phone?: string
  website?: string
  linkedin?: string
  address?: string
  city?: string
  postalCode?: string
  bio: string
}>()

const emit = defineEmits(['update:bio', 'edit'])

const edit = ref(false)
const localBio = ref(props.bio)

watch(
  () => props.bio,
  (v) => {
    if (!edit.value) localBio.value = v
  },
)

function toggleEdit() {
  if (edit.value) emit('update:bio', localBio.value)
  edit.value = !edit.value
}
</script>

<style scoped></style>
