<template>
  <div class="bg-light border-end p-3" style="min-width: 220px;">
    <div class="mb-3">
      <img :src="logo" alt="Logo AltMatch" class="img-fluid" />

      <ul class="nav flex-column mb-3">
        <li class="nav-item nav-hover">
          <a class="nav-link d-flex align-items-center text-dark" href="#">
            <Briefcase class="me-2" :size="20" />
            Mes offres
          </a>
          <a class="nav-link d-flex align-items-center text-dark" href="/company/create-offer">
            <Plus class="me-2" :size="20" />
            Créer offre
          </a>
        </li>
      </ul>
    </div>

    <hr class="mx-2 mb-3" />
    <ul class="nav flex-column">
      <li
        v-for="item in menuItems"
        :key="item.nom"
        class="nav-item mb-2 nav-hover"
      >
        <a class="nav-link d-flex align-items-center text-dark" :href="`/${item.url}`">
          <component :is="item.icone" class="me-2" :size="20" />
          {{ item.nom }}
        </a>
      </li>
    </ul>
  </div>
</template>

<script setup lang="ts">
import { User, Heart, Briefcase, Plus } from 'lucide-vue-next'
import logo from '@/assets/altmatch.png'

const menuItems = [
  { nom: 'Profil', icone: User, url: 'dashboard/company' },
  { nom: 'Mes likes', icone: Heart, url: 'dashboard/company/likes' }
]
</script>

<style scoped>
.nav-hover {
  transition: transform 0.2s ease, background-color 0.2s ease;
  border-radius: 8px;
}
.nav-hover:hover {
  background-color: #d4edda;
  transform: scale(1.03);
}
</style>