<template>
  <div
    class="bg-navbar border-end p-3"
    :class="{ 'd-none d-lg-flex': !visible }"
    style="min-width: 260px; z-index: 2000"
  >
    <div class="w-100 d-flex flex-column h-100">
      <div class="mb-4 text-center">
        <img :src="logo" alt="Logo AltMatch" class="img-fluid" style="max-width: 120px" />
      </div>
      <button class="btn btn-sm btn-light d-lg-none mb-3 align-self-end" @click="$emit('close')">
        ✖
      </button>

      <hr class="mx-2 mb-4" />
      <ul class="nav flex-column">
        <li v-for="item in menuItems" :key="item.nom" class="nav-item mb-2">
          <a class="nav-link d-flex align-items-center nav-link-custom" :href="`/${item.url}`">
            <component :is="item.icone" class="me-2" :size="20" />
            <span>{{ item.nom }}</span>
          </a>
        </li>
      </ul>

      <div class="logout-container mt-auto pt-4">
        <button
          class="btn btn-logout w-100 d-flex align-items-center justify-content-center"
          @click="handleLogout"
        >
          <LogOut class="me-2" :size="20" />
          Se déconnecter
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { User, Heart, Briefcase, Plus, LogOut } from 'lucide-vue-next'
import logo from '@/assets/altmatch.png'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

defineProps<{ visible: boolean }>()
defineEmits(['close'])

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.reset()
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  localStorage.removeItem('user')
  router.push('/')
}

const studentMenu = [
  { nom: 'Profil', icone: User, url: 'dashboard/student' },
  { nom: 'Mes likes', icone: Heart, url: 'dashboard/student/likes' },
  { nom: 'Offres', icone: Briefcase, url: 'dashboard/student/offers' },
]
const companyMenu = [
  { nom: 'Profil', icone: User, url: 'dashboard/company' },
  { nom: 'Créer une offre', icone: Plus, url: 'company/create-offer' },
  { nom: 'Mes candidats', icone: Briefcase, url: 'dashboard/company/candidates' },
  { nom: 'Mes likes', icone: Heart, url: 'dashboard/company/likes/offers' },
]

const role = authStore.role || localStorage.getItem('role')
const menuItems = role === 'ROLE_COMPANY' ? companyMenu : studentMenu
</script>

<style scoped>
.bg-navbar {
  background-color: #5a189a;
  flex-direction: column;
  overflow: hidden;
}
.nav-link-custom {
  color: #fff !important;
  border-radius: 8px;
  transition:
    background 0.2s,
    color 0.2s,
    transform 0.2s;
  font-weight: 500;
  padding: 10px 14px;
}
.nav-link-custom:hover,
.nav-link-custom:focus {
  background-color: #dfeafd !important;
  color: #5a189a !important;
  transform: scale(1.03);
  text-decoration: none;
}
.logout-container {
  margin-top: auto;
}
.btn-logout {
  background: #fff;
  color: #5a189a;
  border: 2px solid #5a189a;
  border-radius: 8px;
  font-weight: 600;
  transition:
    background 0.2s,
    color 0.2s,
    border 0.2s;
  padding: 10px 14px;
}
.btn-logout:hover,
.btn-logout:focus {
  background: #5a189a;
  color: #fff;
  border-color: #5a189a;
}
</style>
