<template>
  <div class="login-wrapper">
    <!-- Gauche : illustration et logo -->
    <div class="login-left">
      <img :src="altmatch" alt="altmatch" class="login-logo" />
      <h1>Bienvenue !</h1>
      <h2 class="login-title">Créer un compte</h2>

      <form @submit.prevent="handleRegister">
        <input
          type="email"
          class="email"
          placeholder="Adresse e-mail"
          v-model="form.email"
          required
        />

        <div class="password-wrapper">
          <input
            :type="showPassword ? 'text' : 'password'"
            class="password"
            placeholder="Mot de passe"
            v-model="form.password"
            required
          />

          <span class="toggle-password" @click="showPassword = !showPassword">
            <svg
              v-if="!showPassword"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
              />
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
              />
            </svg>
            <svg
              v-else
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
              stroke="currentColor"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.141-3.43M9.88 9.88a3 3 0 014.24 4.24M6.1 6.1l11.8 11.8"
              />
            </svg>
          </span>
        </div>
        <p v-if="passwordError" class="text-danger">{{ passwordError }}</p>

        <div class="form-group">
          <label class="label-role">Vous êtes :</label>
          <div class="radio-options">
            <label>
              <input type="radio" name="role" v-model="role" value="student" />
              Un étudiant
            </label>
            <label>
              <input type="radio" name="role" v-model="role" value="company" />
              Une entreprise
            </label>
          </div>
        </div>

        <button type="submit" class="login-button">S'inscrire</button>
      </form>

      <div class="text-right">
        <span>Déjà inscrit ?</span>
        <router-link to="/login" class="register">Connectez-vous</router-link>
      </div>
    </div>

    <!-- Droite : image -->
    <div class="login-right">
      <img :src="signUpImg" alt="Illustration d'inscription" class="login-image" />
      <div class="illustration-credit">
        <a href="https://storyset.com/online" target="_blank" rel="noopener">
          Online illustrations by Storyset
        </a>
      </div>
    </div>
  </div>
</template>


<script setup>
import signUpImg from '../assets/registerWorkin.svg'
import altmatch from '../assets/altmatch.png'
import { reactive } from 'vue'
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const role = ref('student') // valeur par défaut
const showPassword = ref(false)
const router = useRouter()
const authStore = useAuthStore()

const validatePassword = (pwd) => {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&])[A-Za-z\d@$!%*?#&]{6,}$/
  return regex.test(pwd)
}

const form = reactive({
  username: '',
  email: '',
  password: '',
})
const passwordError = ref('')
const handleRegister = async () => {

if (!validatePassword(form.password)) {
    passwordError.value = 'Le mot de passe doit contenir au moins 6 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial.'
    return
  } else {
    passwordError.value = ''
  }

  const payload = {
    email: form.email,
    password: form.password,
    role: role.value,
  }

  try {
    const response = await fetch('https://localhost:8000/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.error || "Erreur lors de l'inscription")
    }

    const { token, role, redirect } = data

    authStore.setAuth(token, role)

    router.push(redirect)
  } catch (error) {
    alert(error.message)
  }
}
</script>

<style scoped src="@/CSS/formAuth.css">

.form-group{
margin-top: 10%;
}

</style>
