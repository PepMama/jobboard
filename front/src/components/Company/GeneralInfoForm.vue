<template>
    <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
        <div class="card-body">
            <h5>Informations générales</h5>
            <form @submit.prevent="onSubmit">
                <div v-for="(label, key) in generalFields" :key="key" class="mb-3">
                    <label class="form-label text-dark">{{ label }}</label>

                    <template v-if="key === 'description'">
                        <textarea v-model="form[key]" class="form-control"></textarea>
                    </template>
                    <template v-else>
                        <input type="text" v-model="form[key]" class="form-control" />
                    </template>
                </div>
                <button type="submit" class="btn btn-success">Mettre à jour</button>
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
            city: '',
            adress: '',
            postalCode: ''
        })
    }
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
    postalCode: 'Code postal :'
}

function onSubmit() {
    emit('update:modelValue', { ...form })
    emit('submit')
}
</script>