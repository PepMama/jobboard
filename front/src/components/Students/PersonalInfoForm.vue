<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
    <div class="card-body">
      <h5>Informations personnelles</h5>

      <form @submit.prevent="onSubmit">
        <div v-for="(label, key) in personalFields" :key="key" class="mb-3">
          <label class="form-label text-dark">{{ label }}</label>

          <template v-if="key === 'description'">
            <textarea
              v-model="form[key]"
              class="form-control"
              :disabled="isPublic"
            ></textarea>
          </template>
          <template v-else>
            <input
              type="text"
              v-model="form[key]"
              class="form-control"
              :disabled="isPublic"
            />
          </template>
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
  isPublic: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const form = props.modelValue

const personalFields = {
  firstname: 'Prénom :',
  name: 'Nom :',
  phone: 'N° de téléphone :',
  age: 'Votre âge :',
  address: 'Adresse :',
  city: 'Ville :',
  postalCode: 'Code postal :'
}

function onSubmit () {
  emit('update:modelValue', { ...form })
  emit('submit')
}
</script>

<style scoped>
</style>
