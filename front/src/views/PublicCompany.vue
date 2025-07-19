<script setup lang="ts">
import { reactive, ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import Sidebar from '@/components/Global/NavBar.vue'
import { ArrowLeft } from 'lucide-vue-next'

import Avatar from '@/assets/avatar-defaut.jpg'
const showSidebar = ref(true)
const route = useRoute()
const name = route.params.name as string
console.log('Company name:', name)

const data = reactive({
  name: '',
  phoneNumber: '',
  website: '',
  linkedin: '',
  address: '',
  city: '',
  postalCode: '',
  description: '',
  industry: '',
  avatar: '',
})

async function fetchCompanyProfile() {
  const t = localStorage.getItem('token');
  if (!t) return;

  const endpoint = `https://localhost:8000/company/by-name/${encodeURIComponent(name)}`;
  try {
    const res = await fetch(endpoint, {
      headers: { Authorization: `Bearer ${t}` },
    });
    if (!res.ok) throw new Error('Entreprise non trouvée');
    const d = await res.json();
    console.log(d);
    data.name = d.name ?? '';
    data.phoneNumber = d.phoneNumber ?? '';
    data.website = d.website ?? '';
    data.linkedin = d.linkedin ?? '';
    data.address = d.address ?? '';
    data.city = d.city ?? '';
    data.postalCode = d.postalCode ?? '';
    data.description = d.description ?? '';
    data.industry = d.industry ?? '';
    data.avatar = d.photo ?? '';
  } catch (e) {
    console.error(e);
  }
}

onMounted(fetchCompanyProfile);

</script>

<template>
  <div class="d-flex flex-column w-100 min-vh-100">
    <div class="d-flex align-items-center px-4 py-3 border-bottom bg-light">
      <button @click="$router.back()" class="btn btn-link text-decoration-none d-flex align-items-center p-0 me-2">
        <ArrowLeft color="black" :size="24" class="me-2" />
        <span class="fs-5 fw-semibold text-dark">Entreprise</span>
      </button>
    </div>
    <div class="d-flex w-100 min-vh-100 dashboard-bg">
      <Sidebar :visible="showSidebar" @close="showSidebar = false" />
      <div class="flex-grow-1 p-4">
        <div class="d-flex align-items-center mb-3">
          <img :src="data.avatar || Avatar" alt="Avatar" class="rounded-circle me-3"
            style="width: 80px; height: 80px;" />
          <h1 class="mb-0">{{ data.name || 'Nom de l\'entreprise inconnu' }}</h1>
        </div>

        <hr />
        <h2>À propos de la compagnie</h2>
        <p v-if="data.description">{{ data.description }}</p>
        <p v-else class="fst-italic text-muted">L'entreprise n'a pas encore de description.</p>

        <hr />
        <div class="d-flex justify-content-between mb-2">
          <strong>Téléphone</strong>
          <span>{{ data.phoneNumber || 'Non renseigné' }}</span>
        </div>

        <hr />
        <div class="d-flex justify-content-between mb-2">
          <strong>Site web</strong>
          <span v-if="data.website">
            <a :href="data.website" target="_blank">{{ data.website }}</a>
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

        <hr />
        <div class="d-flex justify-content-between mb-2 align-items-center">
          <strong>Secteur</strong>
          <span>
            <span v-if="data.industry" class="badge rounded-pill bg-success-subtle text-success px-3 py-1">
              {{ data.industry }}
            </span>
            <span v-else>Non renseigné</span>
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
