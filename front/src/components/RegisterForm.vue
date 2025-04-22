<template>
  <!-- <div class="formGlobal"> -->
  <div class="signupImg" :style="`background-image: url(${signUpImg}); width:100% ; background-size: cover;`"></div>

  <div class="form-container">
    <div class="logo " :style="`background-image: url(${logo}); background-size: cover;`"></div>

    <!-- <div class="logo absolute left-1/2 transform -translate-x-1/2 -top-12"></div> -->
    <div class="min-h-screen flex">
    <!-- Partie image -->

    <!-- Partie formulaire -->
    <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-100">
      <div class="p-8 rounded shadow-md w-full max-w-md">
        <h2>Créer un compte</h2>
        <form @submit.prevent="handleRegister">
          <label for="user">nom d'utilisateur</label>
          <div class="mb-4">
            <input
              v-model="form.username"
              type="text"
              class="username"
              required
            />
          </div>

          <label for="email">Adresse e-mail</label>
          <div class="mb-4">
            <input
              v-model="form.email"
              type="email"
              class="email"
              required
            />
          </div>

          <label for="password">Mot de passe</label>
          <div class="mb-4">
            <input
              v-model="form.password"
              type="password"
              class="password"
              required
            />
          </div>

          <label>Vous êtes :</label><br />
          <div class="radio">
            <label><input type="radio" name="role" v-model="role" value="student" /> Un étudiant</label><br />
            <label><input type="radio" name="role" v-model="role" value="company" /> Une entreprise</label>
          </div>

          <button type="submit" class="inscritpion">
            S'inscrire
          </button>
        </form>
      </div>
    </div>
  </div>
  </div>
<!-- </div> -->
</template>

<script setup>
import signUpImg from '../assets/signUp.png'
import logo from '../assets/logo.PNG'
import { reactive } from 'vue'
import { ref } from 'vue'

const role = ref('student') // valeur par défaut

const form = reactive({
  username: '',
  email: '',
  password: '',
})
const handleRegister = async () => {
  const payload = {
    username: form.username,
    email: form.email,
    password: form.password,
    role: role.value,
  }

  try {
    const response = await fetch('http://localhost:8000/register', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json', 
      },
      body: JSON.stringify(payload), // Convertir le payload en chaîne JSON
    })

    // Vérification si la réponse est OK
    if (!response.ok) {
      throw new Error('Erreur lors de l\'inscription');
    }

    const data = await response.json() // Récupérer la réponse en JSON
    const { token, role, redirect } = data

    // Stockage du token
    localStorage.setItem('token', token)
    localStorage.setItem('role', role)

    // Redirection
    window.location.href = redirect
  } catch (error) {
    alert(error.message || "Erreur lors de l'inscription")
  }
}
</script>

<style >
@import "@/CSS/register.css"; 
</style>

