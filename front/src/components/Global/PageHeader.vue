<template>
  <div class="d-flex align-items-center px-4 py-3 rounded-3 mb-4" style="background-color: #f7faff">
    <button class="btn btn-outline-primary d-lg-none me-3" @click="$emit('toggle-sidebar')">
      ☰
    </button>
    <h2 class="mb-0 fw-bold text-start">{{ title }}</h2>

    <div class="d-flex align-items-center gap-4 ms-auto">
      <span class="text-muted">{{ today }}</span>
      <div class="position-relative" style="width: 24px; height: 24px">
        <Bell class="icon-btn" :size="22" @click="goToMatchs" style="cursor: pointer" />

        <span
          v-if="nbMatches > 0"
          class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
          style="font-size: 0.6rem; min-width: 18px"
        >
          {{ nbMatches }}
        </span>
      </div>
      <Settings class="icon-btn" :size="22" @click="goToSettings" style="cursor: pointer" />
      <LogOut class="icon-btn" :size="22" @click="logout" style="cursor: pointer" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { Bell, Settings, LogOut } from 'lucide-vue-next'

defineProps<{ title: string }>()
defineEmits(['toggle-sidebar'])

const router = useRouter()
const authStore = useAuthStore()
const role = authStore.role || localStorage.getItem('role')
const nbMatches = ref(0)

async function getNumberMatches() {
  const t = localStorage.getItem('token')
  if (!t) {
    console.warn('Token manquant')
    return
  }
  try {
    const res = await fetch('https://localhost:8000/matches/count', {
      headers: { Authorization: `Bearer ${t}` },
    })
    if (!res.ok) {
      throw new Error('Profil non trouvé')
    }
    const data = await res.json()
    return data.matchCount || 0
  } catch (error) {
    console.error('Erreur lors de la récupérationde des matchs:', error)
    return 0
  }
}

onMounted(async () => {
  const matchesCount = await getNumberMatches()
  console.log('Nombre de matchs:', matchesCount)
  nbMatches.value = matchesCount
})

const goToMatchs = () => {
  if (role == 'ROLE_COMPANY') {
    router.push('/dashboard/company/matches')
  } else {
    router.push('/dashboard/student/matches')
  }
}

const today = computed(() => {
  return new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
})

function goToSettings() {
  const role = (authStore.role || localStorage.getItem('role') || '').toLowerCase()
  if (role.includes('company')) {
    router.push('/dashboard/company')
  } else {
    router.push('/dashboard/student')
  }
}

function logout() {
  authStore.reset()
  localStorage.removeItem('token')
  localStorage.removeItem('role')
  localStorage.removeItem('user')
  router.push('/')
}
</script>

<style scoped>
.icon-btn {
  color: #5a189a;
  transition: color 0.2s;
}

.icon-btn:hover {
  color: #891ef4;
}

@media (max-width: 992px) {
  h2 {
    font-size: 1.2rem;
    text-align: center;
    width: 100%;
  }

  .d-flex.align-items-center {
    flex-direction: row;
    gap: 0.5rem;
  }

  .d-flex.align-items-center>.ms-auto {
    margin-left: 0 !important;
    justify-content: center;
  }
}
</style>
