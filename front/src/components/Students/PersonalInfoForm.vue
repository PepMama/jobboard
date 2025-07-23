<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
    <div class="card-body">
      <h5>Informations personnelles</h5>
      <form @submit.prevent="onSubmit">
        <div v-for="(label, key) in personalFields" :key="key" class="mb-3">
          <label class="form-label text-dark">{{ label }}</label>
          <input
            :type="key === 'age' ? 'number' : 'text'"
            v-model="form[key]"
            class="form-control"
            :disabled="isPublic"
          />
        </div>
        <button
          class="btn btn-outline-primary"
          type="submit"
          :disabled="isPublic"
        >
          Mettre à jour
        </button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      firstname: '',
      name: '',
      phone: '',
      age: '',
      address: '',
      city: '',
      postalCode: ''
    })
  },
  isPublic: Boolean
})

const emit = defineEmits<{
  (e: 'submit', payload: Record<string, any>): void
}>()

const form = reactive({ ...props.modelValue })

watch(
  () => props.modelValue,
  newVal => Object.assign(form, newVal)
)

const personalFields = {
  firstname: 'Prénom :',
  name: 'Nom :',
  title: 'Titre (ex : developpeur web) :',
  phone: 'N° de téléphone :',
  age: 'Votre âge :',
  address: 'Adresse :',
  city: 'Ville :',
  postalCode: 'Code postal :'
}

function onSubmit() {
  emit('submit', { ...form })
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
