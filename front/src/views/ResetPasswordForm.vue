<template>
  <div class="container mt-5">
    <h3>Réinitialiser le mot de passe</h3>
    <input v-model="token" placeholder="Mot de passe temporaire" class="form-control mb-2" />
    <input type="password" v-model="password" placeholder="Nouveau mot de passe" class="form-control mb-2" />
    <input type="password" v-model="confirmPassword" placeholder="Confirmez le mot de passe" class="form-control mb-2" />
    <p v-if="passwordMismatch" class="text-danger">Les mots de passe ne sont pas identiques.</p>
    <button class="btn btn-success" @click="submit">Valider</button>
    <p v-if="message" class="mt-3">{{ message }}</p>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

const token = ref('')
const password = ref('')
const confirmPassword = ref('')
const message = ref('')
const router = useRouter()

const passwordMismatch = computed(() => password.value !== confirmPassword.value)

const validatePassword = (pwd) => {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&])[A-Za-z\d@$!%*?#&]{6,}$/
  return regex.test(pwd)
}

 const submit = async () => {
  if (passwordMismatch.value || !validatePassword(password.value)) {
    message.value = 'Mot de passe invalide ou non conforme.'
    return
  }

  const payload = {
    token: token.value,
    newPassword: password.value,
  }

  try {
    const response = await fetch('https://localhost:8000/reset-password', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    })

    if (!response.ok) {
      throw new Error('Réponse non valide')
    }

    message.value = 'Mot de passe mis à jour !'
    setTimeout(() => router.push('/login'), 1500)
  } catch (err) {
    message.value = 'Erreur : mot de passe temporaire invalide ou expiré.'
  }
}

</script>
