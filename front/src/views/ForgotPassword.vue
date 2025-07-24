<template>
  <div class="auth-container">
    <h3>Mot de passe oublié</h3>
    <input v-model="email" placeholder="Votre email" class="form-control mb-3" />
    <button class="btn btn-primary" @click="submit">Envoyer</button>
    <p v-if="message" class="mt-3">{{ message }}</p>
    
    <!-- Nouveau lien vers la page de réinitialisation -->
    <div v-if="emailSent" class="mt-4 p-3 bg-light rounded">
      <p class="mb-2 text-success">✅ Email envoyé ! Vérifiez votre boîte mail.</p>
      <p class="mb-2">Vous avez reçu un mot de passe temporaire par email ?</p>
      <router-link to="/reset-password" class="btn btn-outline-primary">
        <i class="bi bi-key me-2"></i>Cliquez ici pour réinitialiser votre mot de passe
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const message = ref('')
const emailSent = ref(false)

const submit = async () => {
  try {
    await fetch(`${import.meta.env.VITE_API_URL}/forgot-password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ email: email.value })
    })
    
    message.value = 'Si votre email est associé à un compte, un email vous a été envoyé.'
    emailSent.value = true
    
  } catch (err) {
    message.value = 'Erreur : adresse non trouvée.'
    emailSent.value = false
  }
}
</script>

<style scoped src="@/CSS/resertPassword.css"></style>