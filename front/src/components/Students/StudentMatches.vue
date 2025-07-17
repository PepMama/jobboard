<script setup lang="ts">
import { ref, onMounted } from 'vue'
import Avatar from '@/assets/avatar-defaut.jpg'
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'

const companies = ref<Array<any>>([])
const selectedCompany = ref('')
const data = ref({
    name: '',
    phone: '',
    website: '',
    linkedin: '',
    address: '',
    city: '',
    postal_code: '',
    description: '',
    industry: '',
    avatar: '',
})

function selectCompany(name: string) {
    selectedCompany.value = name
    fetchProfile()
}

async function fetchMatches() {
    const t = localStorage.getItem('token')
    if (!t) return

    try {
        const res = await fetch('https://127.0.0.1:8000/matches/student', {
            headers: { Authorization: `Bearer ${t}` },
        })

        const matchData = await res.json()

        companies.value = matchData
            .filter((m: any) => m.company)
            .map((match: any) => {
                const company = match.company
                return {
                    name: company.name,
                    city: company.city ?? '',
                    industry: company.industry ?? '',
                    avatar: company.photo ?? Avatar,
                }
            })

        if (companies.value.length > 0) {
            selectedCompany.value = companies.value[0].name
        }
    } catch (err) {
        console.error(err)
    }
}

async function fetchProfile() {
    const t = localStorage.getItem('token')
    if (!t || !selectedCompany.value) {
        console.warn('Token ou entreprise sélectionnée manquante')
        return
    }

    console.log('Nom entreprise:', selectedCompany.value)

    try {
        const res = await fetch(`https://127.0.0.1:8000/company/${encodeURIComponent(selectedCompany.value)}`, {
            headers: { Authorization: `Bearer ${t}` },
        })

        if (!res.ok) {
            const text = await res.text()
            console.error('Erreur serveur', text)
            throw new Error('Profil non trouvé')
        }

        const d = await res.json()
        data.value.name = d.name ?? ''
        data.value.phone = d.phone_number ?? ''
        data.value.website = d.website ?? ''
        data.value.linkedin = d.linkedin ?? ''
        data.value.address = d.address ?? ''
        data.value.city = d.city ?? ''
        data.value.postal_code = d.postal_code ?? ''
        data.value.description = d.description ?? ''
        data.value.industry = d.industry ?? ''
        data.value.avatar = d.photo ?? Avatar
    } catch (e) {
        console.error('Erreur fetchProfile:', e)
    }
}

onMounted(async () => {
    await fetchMatches()
    await fetchProfile()
})
</script>

<template>
    <div class="d-flex w-100 min-vh-100 dashboard-bg">
        <Sidebar />
        <div class="flex-grow-1 p-4">
            <PageHeader title="Mes matchs" />

            <div class="w-full flex justify-center bg-gray-50 min-h-screen">
                <div class="flex w-full max-w-[1200px] py-10 px-6 gap-6">

                    <div class="w-1/3 bg-white rounded-xl shadow-md p-4 overflow-y-auto max-h-[80vh]">
                        <h2 class="text-xl font-semibold mb-4">Entreprises matchées</h2>

                        <div v-for="(match, index) in companies" :key="index"
                            class="relative border rounded-xl p-4 mb-3 cursor-pointer hover:shadow-md transition"
                            :class="{ 'border-blue-500 shadow-lg': selectedCompany === match.name }"
                            @click="selectCompany(match.name)">
                            <div class="flex items-start gap-4">
                                <img :src="match.avatar" alt="Avatar" height="80" width="80"
                                    class="rounded-full object-cover" />
                                <div class="flex flex-col">
                                    <p class="text-sm font-semibold text-gray-800">{{ match.name }}</p>
                                    <p class="text-xs text-gray-500">{{ match.industry }}</p>
                                    <p class="text-xs text-gray-400">{{ match.city }}</p>
                                </div>
                            </div>
                        </div>
                        <p v-if="companies.length === 0" class="text-gray-500 text-sm">Aucune entreprise trouvée.</p>
                    </div>

                    <div class="w-2/3 bg-white rounded-xl shadow-md p-6">
                        <div v-if="data.name">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-center">
                                    <img :src="data.avatar" alt="Avatar"
                                        class="w-8 h-8 rounded-full mr-4 object-cover" />
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-800">{{ data.name }}</h2>
                                        <p class="text-sm text-gray-500">{{ data.industry }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2 text-gray-700">À propos</h3>
                                <p class="text-gray-600 whitespace-pre-line">{{ data.description }}</p>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-2 text-sm text-gray-600">
                                <div><strong class="text-gray-800">Secteur :</strong> {{ data.industry }}</div>
                                <div><strong class="text-gray-800">Localisation :</strong> {{ data.city }}</div>
                                <div><strong class="text-gray-800">Téléphone :</strong> {{ data.phone }}</div>
                                <div><strong class="text-gray-800">Site web :</strong>
                                    <a :href="data.website" class="text-blue-600 hover:underline" target="_blank">{{
                                        data.website }}</a>
                                </div>
                                <div><strong class="text-gray-800">LinkedIn :</strong>
                                    <a :href="data.linkedin" class="text-blue-600 hover:underline" target="_blank">{{
                                        data.linkedin }}</a>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-gray-500 text-sm p-4">Séléctionnez une entreprise pour voir son profil</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
