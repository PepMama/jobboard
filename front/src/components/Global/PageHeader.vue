<template>
  <div class="d-flex align-items-center px-4 py-3 rounded-3 mb-4" style="background-color: #f7faff;">
    <h2 class="mb-0 fw-bold text-start">{{ title }}</h2>
    <div class="d-flex align-items-center gap-4 ms-auto">
      <span class="text-muted">{{ today }}</span>
      <Bell class="icon-btn" :size="22" />
      <Settings class="icon-btn" :size="22" @click="goToSettings" style="cursor:pointer;" />
      <LogOut class="icon-btn" :size="22" @click="logout" style="cursor:pointer;" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { Bell, Settings, LogOut } from 'lucide-vue-next'

const props = defineProps<{ title: string }>()
const router = useRouter()
const authStore = useAuthStore()

const today = computed(() => {
  return new Date().toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
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
  color: #5651ab;
  transition: color 0.2s;
}
.icon-btn:hover {
  color: #5651ab;
}
</style>