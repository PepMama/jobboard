<template>
  <div
      class="bg-navbar border-end p-3"
      :class="[
        { 'd-none d-lg-flex': !visible },
        { 'sidebar-hidden': !visible && screenWidth < 992 }
      ]"
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
          <router-link 
            :to="`/${item.url}`" 
            class="nav-link d-flex align-items-center nav-link-custom"
            :class="{ 'nav-link-active': isActiveRoute(item.url) }"
          >
            <component :is="item.icone" class="me-2" :size="20" />
            <span>{{ item.nom }}</span>
          </router-link>
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
import { User, Heart, Briefcase, Plus, LogOut, Zap } from 'lucide-vue-next'
import logo from '@/assets/altmatch.png'
import { useAuthStore } from '@/stores/auth'
import { useRouter, useRoute } from 'vue-router'
import { ref, onMounted, onBeforeUnmount } from 'vue'

defineProps<{ visible: boolean }>()
defineEmits(['close'])

const authStore = useAuthStore()
const router = useRouter()
const route = useRoute()

const handleLogout = () => {
  authStore.reset()
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  localStorage.removeItem('user')
  router.push('/')
}

const isActiveRoute = (url: string) => {
  const currentPath = route.path
  const targetPath = `/${url}`
  
  if (url === 'dashboard/student' && currentPath === '/dashboard/student') {
    return true
  }
  if (url === 'dashboard/company' && currentPath === '/dashboard/company') {
    return true
  }
  
  return currentPath === targetPath
}

const studentMenu = [
  { nom: 'Profil', icone: User, url: 'dashboard/student' },
  { nom: 'Offres', icone: Briefcase, url: 'dashboard/student/offers' },
  { nom: 'Mes likes', icone: Heart, url: 'dashboard/student/likes' },
  { nom: 'Mes matchs', icone: Zap, url: 'dashboard/student/matches' },
];
const companyMenu = [
  { nom: 'Profil', icone: User, url: 'dashboard/company' },
  { nom: 'Créer une offre', icone: Plus, url: 'company/create-offer' },
  { nom: 'Les candidats', icone: Briefcase, url: 'dashboard/company/candidates' },
  { nom: 'Mes likes', icone: Heart, url: 'company/likes' },
  { nom: 'Mes matchs', icone: Zap, url: 'dashboard/company/matches' },
  { nom: 'Mes offres', icone: Briefcase, url: 'company/offers' }
];

const role = authStore.role || localStorage.getItem('role')
const menuItems = role === 'ROLE_COMPANY' ? companyMenu : studentMenu

const screenWidth = ref(window.innerWidth)

const updateWidth = () => {
  screenWidth.value = window.innerWidth
}

onMounted(() => {
  window.addEventListener('resize', updateWidth)
})

onBeforeUnmount(() => {
  window.removeEventListener('resize', updateWidth)
})
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
  position: relative;
}

.nav-link-custom:hover,
.nav-link-custom:focus {
  background-color: #dfeafd !important;
  color: #5a189a !important;
  transform: scale(1.03);
  text-decoration: none;
}

.nav-link-active {
  background-color: rgba(255, 255, 255, 0.15) !important;
  color: #fff !important;
  border-left: 4px solid #fff;
  padding-left: 10px;
  position: relative;
}

.nav-link-active::before {
  content: '';
  position: absolute;
  left: 0;
  top: 50%;
  transform: translateY(-50%);
  width: 4px;
  height: 20px;
  background-color: #fff;
  border-radius: 0 2px 2px 0;
}

.nav-link-active:hover {
  background-color: rgba(255, 255, 255, 0.25) !important;
  color: #fff !important;
  transform: translateX(4px);
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

@media (max-width: 991px) {
  .bg-navbar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 260px;
    z-index: 2000;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.2);
    transition: transform 0.3s ease-in-out;
    transform: translateX(0);
  }

  .sidebar-hidden {
    transform: translateX(-100%);
  }

  body {
    overflow-x: hidden;
  }
}
</style>
