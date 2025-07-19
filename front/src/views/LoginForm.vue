<template>
  <div class="login-wrapper">
    <div class="login-left">
      <img :src="altmatch" alt="altmatch" class="login-logo" />
      <h1>Bienvenue !</h1>
        <h2 class="login-title">Connectez vous</h2>
        <form @submit.prevent="handleLogin">
          <input type="email" class="email" placeholder="Email" v-model="email" required />
          <div class="password-wrapper">
            <input
              :type="showPassword ? 'text' : 'password'"
              class="password"
              placeholder="Mot de passe"
              v-model="password"
              required
            />
            <span class="toggle-password" @click="togglePassword">
              <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.141-3.43M9.88 9.88a3 3 0 014.24 4.24M6.1 6.1l11.8 11.8" />
              </svg>
            </span>

          </div>
          <router-link to="/resetPassword" class="forgot-password">Mot de passe oublié ?</router-link><br />
          <button type="submit" class="login-button">Connexion</button>
        </form>
        <div class="text-right">
          
          <span>Pas encore inscrit ?</span> 
          <router-link to="/register" class="register">  Créez un compte</router-link>
        </div>
    </div>

    <div class="login-right">
      <img src="../assets/login.svg" alt="Illustration de connexion" class="login-image" />
      <div class="illustration-credit">
        <a href="https://storyset.com/online" target="_blank" rel="noopener">Online illustrations by Storyset</a>
      </div>
    </div>
  </div>
</template>


<script setup>
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { ref } from 'vue'
import altmatch from '../assets/altmatch.png'

const authStore = useAuthStore()
const router = useRouter()
const email = ref('')
const password = ref('')
const showPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

  const handleLogin = async () => {
  const payload = {
    email: email.value,
    password: password.value
  }

  try {
    const response = await fetch('https://localhost:8000/login', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    })

    if (!response.ok) {
      const data = await response.json()
      throw new Error(data.error || 'Identifiants incorrects')
    }

    const data = await response.json()
    const { token, role } = data

    authStore.setAuth(token, role)
    if (role === 'ROLE_STUDENT') {
      router.push('/dashboard/student/offers')
    } else if (role === 'ROLE_COMPANY') {
      router.push('/dashboard/company/candidates')
    } else {
      router.push('/')
    }
  } catch (error) {
    alert(error.message || 'Erreur lors de la connexion')
  }
}
</script>

<style scoped src="@/CSS/formAuth.css"></style>