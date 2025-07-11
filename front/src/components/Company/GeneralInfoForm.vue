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
            class="form-control"
          />
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
      postal_code: ''
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
  postal_code: 'Code postal :'
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