<template>
    <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
        <div class="card-body">
            <h5>Secteur d'activté</h5>
            <select v-model="props" class="form-select" @click="toggle">
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


const props = defineProps<{ industry: string }>()
const emit  = defineEmits(['update:industry'])

const edit = ref(false)
const localIndustry = ref(props.industry)

watch(() => props.industry, v => { if (!edit.value) localIndustry.value = v })

function toggle () {
  if (edit.value) emit('update:industry', localIndustry.value)
  edit.value = !edit.value
}
</script>