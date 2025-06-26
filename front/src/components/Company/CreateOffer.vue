<template>
    <div class="d-flex w-100 min-vh-100">
        <Sidebar />
        <div class="flex-grow-1 p-4">
            <h1 class="py-4 px-3">Créer une offre</h1>
            <form @submit.prevent="createOffer(formData)">
                <div class="mb-3">
                    <label for="title" class="form-label">Nom de l'offre</label>
                    <input type="text" id="title" class="form-control" v-model="formData.title" />
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea id="description" class="form-control" v-model="formData.description"></textarea>
                </div>

                <div class="mb-3">
                    <label for="state" class="form-label">État</label>
                    <input type="text" id="state" class="form-control" v-model="formData.state" />
                </div>

                <div class="mb-3">
                    <label for="contractType" class="form-label">Type de contrat</label>
                    <select id="contractType" class="form-select" v-model="formData.contractType">
                        <option disabled value="">Sélectionnez le type de contrat</option>
                        <option value="Stage">Stage</option>
                        <option value="Alternance">Alternance</option>
                        <option value="CDI">CDI</option>
                        <option value="CDD">CDD</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="salary" class="form-label">Salaire</label>
                    <input type="number" id="salary" class="form-control" v-model.number="formData.salary" />
                </div>

                <div class="mb-3">
                    <label for="city" class="form-label">Ville</label>
                    <input type="text" id="city" class="form-control" v-model="formData.city" />
                </div>

                <div class="mb-3 form-check">
                    <label class="form-check-label" for="remote">En télétravail ?</label>
                    <input type="checkbox" id="remote" class="form-check-input" v-model="formData.remote" />
                </div>

                <div class="mb-3">
                    <label for="startDate" class="form-label">Date de début</label>
                    <input type="date" id="startDate" class="form-control" v-model="formData.startDate" />
                </div>

                <button type="submit" class="btn btn-success" style="color: #fff;">
                    Créer l'offre
                </button>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import Sidebar from '@/components/Global/NavBar.vue'

const formData = ref({
    title: '',
    description: '',
    state: '',
    contractType: '',
    salary: null as number | null,
    city: '',
    remote: false,
    startDate: ''
})

async function createOffer(offer: typeof formData.value) {
    try {
        const response = await fetch('http://localhost:8000/company/create-offer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(offer)
        })
        if (!response.ok) {
            throw new Error('Erreur lors de la création de l\'offre')
        }
        const data = await response.json()
        console.log('Offre créée :', data)
    } catch (error) {
        console.error('Erreur lors de la création de l\'offre :', error)
    }
}
</script>
