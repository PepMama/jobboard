<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
    <div class="card-body">
      <h5>Secteur d'activité</h5>

      <p v-if="readonly" class="text-muted">
        {{ industry?.trim() ? industry : 'Non renseigné' }}
      </p>

      <select
        v-else
        v-model="localIndustry"
        class="form-select"
        @change="emit('update:industry', localIndustry)"
      >
        <option disabled value="">Sélectionnez un secteur</option>
        <option>Informatique</option>
        <option>Commerce</option>
        <option>Marketing</option>
        <option>Finance</option>
        <option>Santé</option>
        <option>Industrie</option>
        <option>Éducation</option>
        <option>Construction</option>
        <option>Transport</option>
        <option>Tourisme</option>
      </select>
    </div>
  </div>
</template>


<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{
  industry: string
  readonly?: boolean
}>()

const emit = defineEmits(['update:industry'])

const readonly = props.readonly ?? false
const localIndustry = ref(props.industry)

watch(() => props.industry, (val) => {
  localIndustry.value = val
})
</script>
