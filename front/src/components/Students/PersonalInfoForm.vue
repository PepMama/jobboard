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
              @blur="validateField(key)"
            ></textarea>
          </template>
          <template v-else>
            <input
              type="text"
              v-model="form[key]"
              class="form-control"
              :disabled="isPublic"
              @blur="validateField(key)"
            />
          </template>
          <div v-if="errors[key]" class="text-danger small">{{ errors[key] }}</div>
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
import { reactive } from 'vue'

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
      postalCode: '',
      description: ''
    })
  },
  isPublic: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'submit'])

const form = reactive({ ...props.modelValue })
const errors = reactive<Record<string, string | null>>({})

const personalFields: Record<string, string> = {
  firstname: 'Prénom',
  name: 'Nom',
  phone: 'N° de téléphone',
  age: 'Âge',
  address: 'Adresse',
  city: 'Ville',
  postalCode: 'Code postal',
  description: 'Description'
}

const regexes: Record<string, RegExp> = {
  phone: /^((\+33\s?[1-9](?:\s?\d{2}){4})|(0[1-9](?:\s?\d{2}){4}))$/,
  age: /^\d{1,2}$/,
  postalCode: /^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/,
}

function validateField(key: string) {
  if (props.isPublic) {
    errors[key] = null
    return
  }
  const value = String((form as any)[key] ?? '')
  const re = regexes[key]
  if (!re) {
    errors[key] = null
  } else {
    errors[key] = re.test(value)
      ? null
      : `Valeur invalide pour ${personalFields[key]}`
  }
}

function onSubmit() {
  Object.keys(personalFields).forEach(validateField)
  if (Object.values(errors).some(e => e)) {
    return
  }
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