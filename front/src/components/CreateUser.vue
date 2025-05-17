<template>
    <div class="createUserImg"
        :style="`background-image: url(${createUserImg}); width:150%; background-size: contain; background-repeat: no-repeat; background-position: center;`">
    </div>
    <div class="form-container">
        <div class="logo" :style="`background-image: url(${logo}); background-size: cover;`"></div>
        <div class="min-h-screen flex">
            <div class="w-full md:w-1/2 flex items-center justify-center">
                <div>
                    <h2>Créer un compte étudiant</h2>

                    <form @submit.prevent="handleSubmit">
                        <div v-if="etape === 1">
                            <label>Nom</label>
                            <div class="mb-4">
                                <input v-model="form.nom" type="text" />
                            </div>

                            <label>Prénom</label>
                            <div class="mb-4">
                                <input v-model="form.prenom" type="text" />
                            </div>

                            <label>Âge</label>
                            <div class="mb-4">
                                <input v-model="form.age" type="number" />
                            </div>

                            <label>Compétences</label>
                            <div class="mb-4">
                                <input v-model="form.niveauEtude" type="text" placeholder="Python, C++..." />
                            </div>

                            <label>Motivations</label>
                            <div class="mb-4">
                                <input v-model="form.niveauEtude" type="text" placeholder="Ex: Avoir un salaire" />
                            </div>

                            <label>Niveau d'étude</label>
                            <div class="mb-4">
                                <input v-model="form.niveauEtude" type="text" placeholder="Ex: Bac+3, Licence Pro..." />
                            </div>

                            <button type="button" @click="etape = 2" class="boutton">Suivant</button>
                        </div>

                        <div v-else-if="etape === 2">
                            <label>Secteur de recherche</label>
                            <div class="mb-4">
                                <input v-model="form.secteur" type="text"
                                    placeholder="Ex: Informatique, Marketing..." />
                            </div>

                            <label>Domaine</label>
                            <div class="mb-4">
                                <input v-model="form.domaine" type="text"
                                    placeholder="Ex: Développement web, Développeur mobile" />
                            </div>

                            <label>Profession recherchée</label>
                            <div class="mb-4">
                                <input v-model="form.profession" type="text"
                                    placeholder="Ex: Développeur front-end..." />
                            </div>

                            <div class="flex gap-10">
                                <button type="button" @click="etape = 1" class="mr-2">Retour</button>
                                <button type="submit" class="boutton">S'inscrire</button>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import createUserImg from '../assets/registerWorkin.svg'
import logo from '../assets/logo.png'
import { reactive } from 'vue'
import { ref } from 'vue'

const etape = ref(1)

const form = reactive({
    lastname: '',
    firstname: '',
    age: '',
    skills: '',
    motivations: '',
    educationLevel: '',
    sector: '',
    field: '',
    position: '',
})

const handleSubmit = async () => {

}
</script>


<style>
@import '@/CSS/createUser.css';
</style>