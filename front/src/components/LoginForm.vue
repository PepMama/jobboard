<template>
    <div class="loginImg" :style="`background-image: url(${loginImg}); width:100%; background-size: cover;`"></div>
  
    <div class="form-container">
      <div class="logo" :style="`background-image: url(${logo}); background-size: cover;`"></div>
      <div class="min-h-screen flex">
        <div class="w-full md:w-1/2 flex items-center justify-center bg-gray-100">
          <div class="p-8 rounded shadow-md w-full max-w-md">
            <h2>Connexion</h2>
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
              <div class="mb-4">
                <input
                  v-model="form.password"
                  type="password"
                  class="password"
                  required
                />
              </div>
  
              <button type="submit" class="connexion">
                Se connecter
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import loginImg from '../assets/signUp.png' 
  import logo from '../assets/logo.PNG'
  import { reactive } from 'vue'
  
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
      const response = await fetch('http://localhost:8000/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      })
  
      if (!response.ok) {
        throw new Error('Identifiants incorrects')
      }
  
      const data = await response.json()
      const { token, role, redirect } = data
  
      localStorage.setItem('token', token)
      localStorage.setItem('role', role)
  
      window.location.href = redirect
    } catch (error) {
      alert(error.message || 'Erreur lors de la connexion')
    }
  }
  </script>
  
  <style>
  @import "@/CSS/login.css"; 
  </style>
  