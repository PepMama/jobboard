<template>
  <div class="auth-container">
    <h3>Réinitialiser le mot de passe</h3>
    
    <div class="alert alert-info mb-3">
      <i class="bi bi-info-circle me-2"></i>
      Saisissez le mot de passe temporaire reçu par email et votre nouveau mot de passe.
    </div>
    
    <input 
      v-model="token" 
      placeholder="Mot de passe temporaire (reçu par email)" 
      class="form-control mb-2" 
    />
    <input 
      type="password" 
      v-model="password" 
      placeholder="Nouveau mot de passe" 
      class="form-control mb-2" 
    />
    <input 
      type="password" 
      v-model="confirmPassword" 
      placeholder="Confirmez le nouveau mot de passe" 
      class="form-control mb-2" 
    />
    
    <small class="text-muted mb-3 d-block">
      Le mot de passe doit contenir au moins 6 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.
    </small>
    
    <p v-if="passwordMismatch" class="text-danger">Les mots de passe ne sont pas identiques.</p>
    
    <button class="btn btn-success" @click="submit" :disabled="passwordMismatch">
      Valider
    </button>
    
    <p v-if="message" class="mt-3" :class="message.includes('mis à jour') ? 'text-success' : 'text-danger'">
      {{ message }}
    </p>
    
    <!-- Lien de retour -->
    <div class="mt-3">
      <router-link to="/forgot-password" class="text-muted">
        ← Retour à la demande de réinitialisation
      </router-link>
    </div>
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

const passwordMismatch = computed(() => 
  password.value !== confirmPassword.value && confirmPassword.value !== ''
)

const validatePassword = (pwd) => {
  const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&])[A-Za-z\d@$!%*?#&]{6,}$/
  return regex.test(pwd)
}

const submit = async () => {
  if (passwordMismatch.value || !validatePassword(password.value)) {
    message.value = 'Mot de passe invalide ou non conforme aux exigences.'
    return
  }

  const payload = {
    token: token.value,
    newPassword: password.value,
  }

  try {
    const response = await fetch(`${import.meta.env.VITE_API_URL}/reset-password`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(payload),
    })

    if (!response.ok) {
      throw new Error('Réponse non valide')
    }

    message.value = '✅ Mot de passe mis à jour avec succès !'
    setTimeout(() => router.push('/login'), 2000)
    
  } catch (err) {
    message.value = '❌ Erreur : mot de passe temporaire invalide ou expiré.'
  }
}
</script>

<style scoped src="@/CSS/resertPassword.css"></style>
