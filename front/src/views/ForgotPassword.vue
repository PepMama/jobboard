<template>
  <div class="container mt-5">
    <h3>Mot de passe oublié</h3>
    <input v-model="email" placeholder="Votre email" class="form-control mb-3" />
    <button class="btn btn-primary" @click="submit">Envoyer</button>
    <p v-if="message" class="mt-3">{{ message }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const email = ref('')
const message = ref('')

const submit = async () => {
  try {
    await fetch('https://localhost:8000/forgot-password', 
    {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    body: JSON.stringify({ email: email.value })
  })
    message.value = 'Si votre email est associé à un compte, un email vous a été envoyé.'
  } catch (err) {
    message.value = 'Erreur : adresse non trouvée.'
  }
}
</script>
