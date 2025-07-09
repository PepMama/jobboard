<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
    <div class="card-body">
      <h5>Informations générales</h5>
      <form @submit.prevent="onSubmit">
        <div v-for="(label, key) in generalFields" :key="key" class="mb-3">
          <label class="form-label text-dark">{{ label }}</label>
          <input type="text" v-model="form[key]" class="form-control" :disabled="readonly" />
        </div>
        <button v-if="!readonly" type="submit" class="btn btn-success">Mettre à jour</button>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { readonly } from 'vue'

const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({
      name: '',
      phone: '',
      website: '',
      linkedin: '',
      city: '',
      adress: '',
      postal_code: '',
    }),
  },
  readonly: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['update:modelValue', 'submit'])

const form = props.modelValue

const generalFields = {
  name: 'Nom :',
  phone: 'N° de téléphone :',
  website: 'Site internet :',
  linkedin: 'LinkedIn :',
  address: 'Adresse :',
  city: 'Ville :',
  postal_code: 'Code postal :',
}

function onSubmit() {
  emit('update:modelValue', { ...form })
  emit('submit')
}
</script>
