<template>
  <div
    class="signupImg"
    :style="`background-image: url(${signUpImg}); width:150%; background-size: contain; background-repeat: no-repeat; background-position: center;`"
  ></div>

  <div class="form-container">
    <div class="logo" :style="`background-image: url(${logo}); background-size: cover;`"></div>
    <div class="min-h-screen flex">
      <div class="w-full md:w-1/2 flex items-center justify-center">
        <div>
          <h2>Créer un compte</h2>
          <form @submit.prevent="handleRegister">
            <label for="email">Adresse e-mail</label>
            <div class="mb-4">
              <input
                v-model="form.email"
                type="email"
                class="email"
                name="email"
                placeholder="Ex: lorenayawadio@adresse.fr"
                required
              />
            </div>

            <label for="password">Mot de passe</label>
            <div class="mb-4 password-wrapper">
              <input
                :type="showPassword ? 'text' : 'password'"
                v-model="form.password"
                class="password"
                name="password"
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

            <label>Vous êtes :</label><br />
            <div class="radio">
              <label
                ><input type="radio" name="role" v-model="role" value="student" /> Un
                étudiant</label
              ><br />
              <label
                ><input type="radio" name="role" v-model="role" value="company" /> Une
                entreprise</label
              >
            </div>

            <button type="submit" class="inscritpion">S'inscrire</button>
            <!-- <div class="mb-2 text-right">
              <a href="/logIn" class="logIn">Vous avez déja un compte ? connectez-vous ?</a>
            </div> -->
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import signUpImg from '../assets/registerWorkin.svg'
import logo from '../assets/logo.png'
import { reactive } from 'vue'
import { ref } from 'vue'

const role = ref('student') // valeur par défaut
const showPassword = ref(false)

const form = reactive({
  username: '',
  email: '',
  password: '',
})
const handleRegister = async () => {
  const payload = {
    email: form.email,
    password: form.password,
    role: role.value,
  };

  try {
    const response = await fetch('https://127.0.0.1:8000/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.error || "Erreur lors de l'inscription");
    }

    localStorage.setItem('token', data.token);
    localStorage.setItem('role', data.role);

    // window.location.href = '/login';
  } catch (error) {
    alert(error.message);
  }
};

</script>

<style>
@import '@/CSS/register.css';
</style>
