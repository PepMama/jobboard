<template>
    <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <h5 class="mb-0">Secteur d'activité</h5>
            </div>
            
            <div v-if="!edit">
                <div class="industry-display p-3 bg-light rounded-2 mb-3">
                    <div class="d-flex align-items-center">
                        <i :class="getIndustryIcon(industry)" class="me-2 text-primary"></i>
                        <span class="fw-semibold">{{ industry || 'Aucun secteur défini' }}</span>
                    </div>
                    <small v-if="industry" class="text-muted d-block mt-1">
                        {{ getIndustryDescription(industry) }}
                    </small>
                </div>
                <button class="btn btn-outline-primary" @click="edit = true">
                    <i class="bi bi-pencil me-2"></i>Modifier le secteur
                </button>
            </div>
            
            <div v-else>
                <div class="mb-3">
                    <label class="form-label text-muted">Sélectionnez votre secteur d'activité :</label>
                    <select v-model="localIndustry" class="form-select form-select-lg">
                        <option disabled value="">Choisissez un secteur...</option>
                        <option v-for="sector in sectors" :key="sector.value" :value="sector.value">
                            {{ sector.label }}
                        </option>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-primary" @click="save">
                        <i class="bi bi-check-lg me-2"></i>Sauvegarder
                    </button>
                    <button class="btn btn-outline-secondary" @click="cancel">
                        <i class="bi bi-x-lg me-2"></i>Annuler
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{ industry: string }>()
const emit = defineEmits(['update:industry'])

const edit = ref(false)
const localIndustry = ref(props.industry)

const sectors = [
    { value: 'Informatique', label: '💻 Informatique & Technologies' },
    { value: 'Commerce', label: '🛍️ Commerce & Vente' },
    { value: 'Marketing', label: '📢 Marketing & Communication' },
    { value: 'Finance', label: '💰 Finance & Banque' },
    { value: 'Santé', label: '🏥 Santé & Médical' },
    { value: 'Industrie', label: '🏭 Industrie & Manufacturing' },
    { value: 'Éducation', label: '🎓 Éducation & Formation' },
    { value: 'Construction', label: '🏗️ Construction & BTP' },
    { value: 'Transport', label: '🚛 Transport & Logistique' },
    { value: 'Tourisme', label: '✈️ Tourisme & Hôtellerie' },
    { value: 'Autre', label: 'Autre...' },
]

watch(() => props.industry, v => { 
    if (!edit.value) localIndustry.value = v 
})

function save() {
    emit('update:industry', localIndustry.value)
    edit.value = false
}

function cancel() {
    localIndustry.value = props.industry
    edit.value = false
}

function getIndustryIcon(industry: string): string {
    const iconMap: { [key: string]: string } = {
        'Informatique': 'bi bi-laptop',
        'Commerce': 'bi bi-shop',
        'Marketing': 'bi bi-megaphone',
        'Finance': 'bi bi-currency-dollar',
        'Santé': 'bi bi-heart-pulse',
        'Industrie': 'bi bi-gear',
        'Éducation': 'bi bi-mortarboard',
        'Construction': 'bi bi-hammer',
        'Transport': 'bi bi-truck',
        'Tourisme': 'bi bi-airplane'
    }
    return iconMap[industry] || 'bi bi-building'
}

function getIndustryDescription(industry: string): string {
    const descriptions: { [key: string]: string } = {
        'Informatique': 'Développement, IT, logiciels, technologies',
        'Commerce': 'Vente, retail, e-commerce, distribution',
        'Marketing': 'Publicité, communication, digital marketing',
        'Finance': 'Banque, assurance, investissement, comptabilité',
        'Santé': 'Médical, pharmaceutique, dispositifs médicaux',
        'Industrie': 'Manufacturing, production, ingénierie',
        'Éducation': 'Formation, enseignement, recherche',
        'Construction': 'BTP, architecture, travaux publics',
        'Transport': 'Logistique, livraison, mobilité',
        'Tourisme': 'Hôtellerie, voyage, loisirs'
    }
    return descriptions[industry] || 'Secteur d\'activité professionnel'
}
</script>

<style scoped>
.card {
  border-radius: 18px !important;
  box-shadow: 0 2px 16px 0 #e3e8f7 !important;
  border: none !important;
}

.industry-icon {
  width: 50px;
  height: 50px;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.industry-icon i {
  color: white !important;
}

.industry-display {
  border: 2px dashed #e9ecef;
  transition: all 0.3s ease;
}

.industry-display:hover {
  border-color: #6c757d;
  background-color: #f8f9fa !important;
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 8px;
  font-weight: 500;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
  transform: translateY(-1px);
}

.btn-outline-primary {
  color: #667eea;
  border-color: #667eea;
  border-radius: 8px;
  font-weight: 500;
}

.btn-outline-primary:hover {
  background: #667eea;
  border-color: #667eea;
  transform: translateY(-1px);
}

.btn-outline-secondary {
  border-radius: 8px;
  font-weight: 500;
}

.form-select-lg {
  border-radius: 12px;
  border: 2px solid #e9ecef;
  padding: 12px 16px;
  font-size: 1rem;
}

.form-select-lg:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
</style>