<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Sidebar from '@/components/Global/NavBar.vue'
import { ArrowLeft } from 'lucide-vue-next'
import PageHeader from '@/components/Global/PageHeader.vue'

import Avatar from '@/assets/avatar-defaut.jpg'
const showSidebar = ref(true)
const route = useRoute()
const name = route.params.name as string
console.log('Company name:', name)

const data = reactive({
  name: '',
  firstName: '',
  phoneNumber: '',
  github: '',
  linkedin: '',
  address: '',
  city: '',
  postalCode: '',
  description: '',
  photo: '',
})

async function fetchStudentProfile() {
  const t = localStorage.getItem('token');
  if (!t) return;

  const endpoint = `https://localhost:8000/student/by-name/${encodeURIComponent(name)}`;
  try {
    console.log('Fetching student profile for:', name);
    const res = await fetch(endpoint, {
      headers: { Authorization: `Bearer ${t}` },
    });
    if (!res.ok) throw new Error('Étudiant non trouvé');
    const d = await res.json();
    console.log(d);
    data.name = d.name ?? '';
    data.firstName = d.firstname ?? '';
    data.phoneNumber = d.phoneNumber ?? '';
    data.github = d.github ?? '';
    data.linkedin = d.linkedin ?? '';
    data.address = d.address ?? '';
    data.city = d.city ?? '';
    data.postalCode = d.postalCode ?? '';
    data.description = d.description ?? '';
    data.photo = d.photo ?? '';
  } catch (e) {
    console.error(e);
  }
}

onMounted(fetchStudentProfile);

</script>

<template>
  <div class="d-flex flex-column w-100 min-vh-100">
    <div class="d-flex w-100 min-vh-100 dashboard-bg">
      <Sidebar :visible="showSidebar" @close="showSidebar = false" />
      <div class="flex-grow-1 p-4">
        <PageHeader title="Mes matchs" @toggle-sidebar="showSidebar = true" />
        <div class="d-flex align-items-center px-4 py-3 border-bottom bg-light">
          <button @click="$router.back()" class="btn btn-link text-decoration-none d-flex align-items-center p-0 me-2">
            <ArrowLeft color="black" :size="24" class="me-2" />
            <span class="fs-5 fw-semibold text-dark">Entreprise</span>
          </button>
        </div>
        <div class="d-flex align-items-center mb-3">
          <img :src="data.photo || Avatar" alt="Avatar" class="rounded-circle me-3"
            style="width: 80px; height: 80px;" />
          <h1 class="mb-0">{{ data.firstName || '' }} {{ data.name || 'Nom de l\'étudiant inconnu' }}</h1>
        </div>

        <hr />
        <h2>À propos de l'étudiant</h2>
        <p v-if="data.description">{{ data.description }}</p>
        <p v-else class="fst-italic text-muted">L'étudiant n'a pas encore de description.</p>

        <hr />
        <div class="d-flex justify-content-between mb-2">
          <strong>Téléphone</strong>
          <span>{{ data.phoneNumber || 'Non renseigné' }}</span>
        </div>

        <hr />
        <div class="d-flex justify-content-between mb-2">
          <strong>Site web</strong>
          <span v-if="data.github">
            <a :href="data.github" target="_blank">{{ data.github }}</a>
          </span>
          <span v-else>Non renseigné</span>
        </div>

        <hr />
        <div class="d-flex justify-content-between mb-2">
          <strong>LinkedIn</strong>
          <span v-if="data.linkedin">
            <a :href="data.linkedin" target="_blank">{{ data.linkedin }}</a>
          </span>
          <span v-else>Non renseigné</span>
        </div>
        <hr>
        <div class="d-flex justify-content-between mb-2 align-items-center">
          <strong>Secteur</strong>
          <span>
            <span v-if="data.github" class="badge rounded-pill bg-success-subtle text-success px-3 py-1">
              {{ data.github }}
            </span>
            <span v-else>Non renseigné</span>
          </span>
        </div>

        <hr />
        <div class="d-flex justify-content-between mb-2">
          <strong>Adresse</strong>
          <span>{{ data.address || 'Non renseigné' }}</span>
        </div>

        <hr />
        <div class="d-flex justify-content-between mb-2 align-items-center">
          <strong>Ville</strong>
          <span>
            <span v-if="data.city" class="badge rounded-pill bg-primary-subtle text-primary px-3 py-1">
              {{ data.city }}
            </span>
            <span v-else>Non renseigné</span>
          </span>
        </div>

        <hr />
        <div class="d-flex justify-content-between mb-2 align-items-center">
          <strong>Code postal</strong>
          <span>
            <span v-if="data.postalCode" class="badge rounded-pill bg-secondary-subtle text-secondary px-3 py-1">
              {{ data.postalCode }}
            </span>
            <span v-else>Non renseigné</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
