<template>
  <div class="login-form-container">
    <div class="logo" :style="`background-image: url(${logo}); background-size: cover;`"></div>
    <div class="min-h-screen flex">
      <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-100">
        <div class="p-8 rounded shadow-md w-full max-w-md">
          <h2 class="login-title">Connexion</h2>
          <form @submit.prevent="handleLogin">
            <label for="email">Adresse e-mail</label>
            <div class="mb-4">
              <input
                v-model="form.email"
                type="email"
                class="email"
                placeholder="Ex: utilisateur@exemple.com"
                required
              />
            </div>

            <label for="password">Mot de passe</label>
            <div class="mb-4 password-wrapper">
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="form.password"
                class="password"
                required
              />
              <span class="toggle-password" @click="showPassword = !showPassword">
                <svg
                  v-if="showPassword"
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7zm0 12c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5z"
                  />
                  <path d="M12 9c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3z" />
                </svg>
                <svg
                  v-else
                  xmlns="http://www.w3.org/2000/svg"
                  fill="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path
                    d="M12 5c-7 0-10 7-10 7s3 7 10 7c2.5 0 4.6-1 6.2-2.3l1.8 1.8 1.4-1.4-18-18-1.4 1.4 3.2 3.2c-1.3.9-2.3 2-3.2 3.3 0 0 3 7 10 7 1.6 0 3.2-.4 4.5-1l1.5 1.5c-1.8 1.1-4 1.5-6 1.5-7 0-10-7-10-7s3-7 10-7c2.2 0 4.2.6 6 1.5l-1.5 1.5c-1.3-.6-2.9-1-4.5-1z"
                  />
                </svg>
              </span>
            </div>
            <button type="submit" class="login-button">Se connecter</button>
            <div class="mb-2 text-right">
              <a href="/reset-password" class="forgot-password">Mot de passe oublié ?</a>
            </div>
            <div class="mb-2 text-right">
              <a href="/" class="register">Vous n'êtes pas encore inscrit? Inscrivez-vous</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import loginImg from '../assets/login.svg'
import logo from '../assets/logo.png'
import { reactive } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const form = reactive({
  email: '',
  password: '',
})

const handleLogin = async () => {
  const payload = {
    email: form.email,
    password: form.password,
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
.login-form-container {
  display: flex;
  flex-direction: column;
  background-color: #e2eee4;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 4rem;
  margin: 10% 0% 0% 30% ;
  position: relative;
  border-radius: 10px 10px 10px 10px;
  width: 40%;
}
.login-title {
  color: rgb(39, 38, 38);
  font-size: 1.25rem;
  margin-bottom: 1.5rem;
  margin-left: 40%;
  font-weight: 700;
  font-family:
    system-ui,
    -apple-system,
    BlinkMacSystemFont,
    'Segoe UI',
    Roboto,
    Oxygen,
    Ubuntu,
    Cantarell,
    'Open Sans',
    'Helvetica Neue',
    sans-serif;
}
.loginImg {
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 10px 0px 0px 10px;
  background-color: rgb(234, 233, 233);
  margin-left: -40%;
  width: 100%;
}

.login-input {
  background-color: #eee;
  border-radius: 5px;
  padding: 10px;
  font-size: 1rem;
}

.login-button {
  background-color: #8e44ad;
  color: white;
  font-weight: bold;
  /* border-radius: 5px ; */
}

.password-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-wrapper input {
  padding-right: 2.5rem;
}

.toggle-password {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  cursor: pointer;
  color: #555;
  height: 20px;
  width: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.toggle-password svg {
  width: 20px;
  height: 20px;
  transition: transform 0.2s ease;
}

.toggle-password:hover svg {
  transform: scale(1.1);
  color: #8e44ad;
}
.forgot-password {
    font-weight: 500;
  }
  .register{
    font-weight: 500;
  }
  .text-right{
     margin-top: 2%;
  }
  
.text-right a {
  text-decoration: none ;
  color: #555;
}

.text-right a:hover {
  color: #732d91; 
  transition: color 0.3s ease; 
}


</style>
