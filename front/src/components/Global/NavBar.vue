<template>
  <div class="bg-light border-end p-3" style="min-width: 220px;">
    <div class="mb-3">
      <img :src="logo" alt="Logo AltMatch" class="img-fluid" />
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
    <div class="logout-container mt-auto pt-4 align-content-center">
      <button class="btn w-100 nav-hover" @click="handleLogout">
        <LogOut />
        Se déconnecter
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { User, Heart, Briefcase, Plus, LogOut } from 'lucide-vue-next'
import logo from '@/assets/altmatch.png'
import { useAuthStore } from '@/stores/auth';
import { useRouter } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();

const handleLogout = () => {
  authStore.reset()
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  localStorage.removeItem('user')
  router.push('/')
}

// Menus selon le rôle
const studentMenu = [
  { nom: 'Profil', icone: User, url: 'dashboard/student' },
  { nom: 'Mes likes', icone: Heart, url: 'dashboard/student/likes' },
  { nom: 'Offres', icone: Briefcase, url: 'dashboard/student/offers' }
]
const companyMenu = [
  { nom: 'Profil', icone: User, url: 'dashboard/company' },
  { nom: 'Créer une offre', icone: Plus, url: 'company/create-offer' },
  { nom: 'Mes offres', icone: Briefcase, url: 'dashboard/company' }  
]

// Récupère le rôle depuis le store ou le localStorage
const role = authStore.role || localStorage.getItem('role')
const menuItems = role === 'ROLE_COMPANY' ? companyMenu : studentMenu
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

.bg-light {
  display: flex;
  flex-direction: column;
  height: 100vh;
  overflow: hidden;
}

.logout-container {
  margin-top: auto;
}
</style>