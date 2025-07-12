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
    const { token, role, redirect } = data

    authStore.setAuth(token, role)
    router.push(redirect)
  } catch (error) {
    alert(error.message || 'Erreur lors de la connexion')
  }
}
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap');

@keyframes fadeIn {
  from {
    transform: translateY(20px);
    opacity: 0;
  }
  to {
    transform: translateY(0);
    opacity: 1;
  }
}
h1{
  letter-spacing: 0.5px;
  font-size: 3rem;
  font-weight: 700;
   color: #5a189a;
}

.login-title {
  font-size: 1.5rem;
  margin-top: 1%;
  color: rgb(85, 85, 85);
  margin-bottom: 5rem;
  letter-spacing: 0.5px;
}

.email,
.password {
  width: 80%;
  padding: 1rem;
  border: 2px solid #ddd;
  border-radius: 1rem;
  background-color: #ffffff;
  font-size: 1rem;
  outline: none;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.email:focus,
.password:focus {
  border-color: #9d4edd;
  box-shadow: 0 0 0 4px rgba(157, 78, 221, 0.2);
}

.login-button {
  width: 50%;
  padding: 0.9rem;
  background: linear-gradient(135deg, #9d4edd, #7b2cbf);
  border: none;
  border-radius: 1rem;
  color: #fff;
  font-weight: 700;
  font-size: 1rem;
  margin-top: 1.5rem;
  cursor: pointer;
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.login-button:hover {
  transform: scale(1.03);
  box-shadow: 0 6px 15px rgba(123, 44, 191, 0.3);
}

.password-wrapper {
  position: relative;
  width: 80%;
  margin-top: 2%;
  margin-bottom: 1rem;
}

.password-wrapper input.password {
  width: 100%; 
  padding-right: 3rem; 
  padding-left: 1rem;
  padding-top: 1rem;
  padding-bottom: 1rem;
  border: 2px solid #ddd;
  border-radius: 1rem;
  font-size: 1rem;
  background-color: #ffffff;
  outline: none;
  transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.toggle-password {
  position: absolute;
  right: 1rem; /* garde un peu d'espace du bord */
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #bbb;
  transition: color 0.2s ease;
}

.toggle-password:hover {
  color: #7b2cbf;
}

.toggle-password svg {
  width: 22px;
  height: 22px;
}

.text-right {
  text-align: left;
  margin-top: 5rem;
}

.text-right a {
  margin-left: 10px;
  text-decoration: none;
  font-size: 0.95rem;
  font-weight: 600;
  transition: color 0.2s ease;
  margin-top: 0.3rem;
  color:#7b2cbf;
}
 .forgot-password{
  margin-top: 10%;
  margin-left: 60%;
  text-decoration: none;
  color:rgb(156, 156, 156);
  font-size: 0.95rem;
  font-weight: 600;
 }
 .forgot-password:hover{
  color: #7b2cbf;
 }

.register {
  display: inline-block;
  margin-top: 3rem;
}
.login-wrapper {
  display: flex;
  min-height: 100vh;
  background-color: #f3f0ff;
}

.login-left,
.login-right {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 3rem;
}

.login-left {
  background-color: #f8f9fc;
  position: relative;
}

.login-logo {
  position: absolute;
  top: 2rem;
  left: 2rem;
  width: 120px;
  height: auto;
}

.login-right {
  background-color: #7b2cbf;;
  align-items: center;
  justify-content: center;
  text-align: center;
  position: relative;
}

.login-image {
  max-width: 90%;
  height: auto;
}

.illustration-credit {
  margin-top: 1rem;
  font-size: 0.85rem;
  color: #4b0082;
}

.illustration-credit a {
  color: #4b0082;
  text-decoration: none;
}

.illustration-credit a:hover {
  text-decoration: underline;
}

/* Responsive */
@media (max-width: 768px) {
  .login-left {
    margin: 10% 1rem;
    padding: 2rem;
  }
}
</style>