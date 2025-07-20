<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
    <div class="card-body">
      <h5>Informations générales</h5>

      <form @submit.prevent="onSubmit">
        <div v-for="(label, key) in generalFields" :key="key" class="mb-3">
          <label class="form-label text-dark">{{ label }}</label>
          <input
            type="text"
            v-model="form[key]"
            :maxlength="getMaxLength(key)"
            class="form-control"
            :placeholder="getPlaceholder(key)"
            @input="validateSiret(key, $event)"
          />
          <small v-if="key === 'siret'" class="form-text text-muted">
            Le numéro SIRET doit contenir exactement 14 chiffres
          </small>
        </div>

        <button
          class="btn btn-outline-primary"
          type="submit"
        >
          Mettre à jour
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      name: '',
      phone: '',
      website: '',
      linkedin: '',
      address: '',
      city: '',
      postal_code: '',
      siret: ''
    })
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const form = props.modelValue

const generalFields = {
  name: 'Nom de l\'entreprise :',
  phone: 'N° de téléphone :',
  website: 'Site internet :',
  linkedin: 'LinkedIn :',
  address: 'Adresse :',
  city: 'Ville :',
  postal_code: 'Code postal :',
  siret: 'SIRET :'
}

function getMaxLength(key: string): number {
  const maxLengths: Record<string, number> = {
    siret: 14,
    postal_code: 10,
    phone: 20
  }
  return maxLengths[key] || 255
}

function getPlaceholder(key: string): string {
  const placeholders: Record<string, string> = {
    siret: '12345678901234',
    postal_code: '75001',
    phone: '0123456789'
  }
  return placeholders[key] || ''
}

function validateSiret(key: string, event: Event) {
  if (key === 'siret') {
    const target = event.target as HTMLInputElement
    // Supprime tous les caractères non numériques
    target.value = target.value.replace(/\D/g, '')
    form[key] = target.value
  }
}

function onSubmit() {
  emit('update:modelValue', { ...form })
  emit('submit')
}
</script>

<style scoped>
.btn-outline-primary {
  color: #5651ab;
  border-color: #5651ab;
  transition: background-color 0.3s, color 0.3s, border-color 0.3s;
}
.btn-outline-primary:hover {
  background: #5651ab;
  color: #fff;
  border-color: #5651ab;
}
</style>