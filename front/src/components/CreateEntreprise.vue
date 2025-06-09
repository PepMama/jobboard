<template>
    <div class="d-flex w-100" style="min-height: 100vh;">
        <div class="bg-light border-end p-3" style="min-width: 200px; min-height: 100vh;">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a class="nav-link text-dark" href="">Profil</a>
                </li>
                <li class="nav-item mb-2">
                    <a class="nav-link text-dark" href="">Mes likes</a>
                </li>
            </ul>
        </div>

        <div class="flex-grow-1">
            <div class="py-4 px-3 w-100">
                <div class="d-flex flex-nowrap gap-4" style="overflow-x: auto;">

                    <!-- Colonne gauche -->
                    <div class="flex-fill" style="min-width: 400px; max-width: 60%;">

                        <div class="card bg-white text-dark rounded-3 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <img src="https://resize.elle.fr/portrait_1280/var/plain_site/storage/images/loisirs/series/de-gossip-girl-a-you-5-choses-que-vous-ignoriez-sur-penn-badgley-3829250/92305759-1-fre-FR/De-Gossip-Girl-a-You-5-choses-que-vous-ignoriez-sur-Penn-Badgley.jpg"
                                    alt="Logo" class="rounded-circle me-3" width="80" height="80" />
                                <h5 class="mb-0">Nom de l'entreprise</h5>
                            </div>
                        </div>

                        <!-- Card infos -->
                        <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
                            <div class="card-body">
                                <h5>Informations générales</h5>
                                <form @submit.prevent>
                                    <div class="mb-3">
                                        <label class="form-label text-dark">Adresse :</label>
                                        <input type="text" class="form-control" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-dark">Site web :</label>
                                        <input type="url" class="form-control" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-dark">LinkedIn :</label>
                                        <input type="url" class="form-control" />
                                    </div>
                                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne de droite -->
                    <div class="flex-fill" style="min-width: 300px; max-width: 30%;">
                        <!-- secteur -->
                        <div class="card bg-white text-dark rounded-3 shadow-sm">
                            <div class="card-body">
                                <h5>Secteur d'activté</h5>
                                <select v-model="secteur" class="form-select">
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

                        <!-- Card valeurs -->
                        <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
                            <div class="card-body">
                                <h5>Nos valeurs</h5>
                                <ul class="list-group list-group-flush">
                                    <li v-for="(valeur, index) in valeurs" :key="index"
                                        class="list-group-item shadow-sm rounded-3 text-dark mb-2"
                                        style="background-color: #d4edda;">
                                        {{ valeur }}
                                    </li>
                                </ul>
                                <button class="btn btn-success mt-3" data-bs-toggle="modal"
                                    data-bs-target="#valeurModal">Ajouter une valeur</button>
                            </div>
                        </div>

                        <!-- Card besoins -->
                        <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
                            <div class="card-body">
                                <h5>Nos besoins</h5>
                                <ul class="list-group list-group-flush">
                                    <li v-for="(besoin, index) in besoins" :key="index"
                                        class="list-group-item shadow-sm rounded-3 text-dark mb-2"
                                        style="background-color: #d4edda;">
                                        {{ besoin }}
                                    </li>
                                </ul>
                                <button class="btn btn-success mt-3" data-bs-toggle="modal"
                                    data-bs-target="#besoinModal">Ajouter un besoin</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modale pour les valeurs -->
                <div class="modal fade" id="valeurModal" tabindex="-1" aria-labelledby="valeurModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="valeurModalLabel">Ajouter une valeur</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input v-model="nouvelleValeur" type="text" class="form-control"
                                    placeholder="Ex: Inclusion, Transparence..." />
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-success" @click="ajouterValeur"
                                    data-bs-dismiss="modal">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modale pour les besoins -->
                <div class="modal fade" id="besoinModal" tabindex="-1" aria-labelledby="besoinModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="besoinModalLabel">Ajouter un besoin</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input v-model="nouveauBesoin" type="text" class="form-control"
                                    placeholder="Ex: Alternant développeur React" />
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-success" @click="ajouterBesoin"
                                    data-bs-dismiss="modal">Ajouter</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            secteur: '',
            valeurs: ['Chalereux', 'Innovation'],
            besoins: ['Alternant', 'Stagiaire', 'Développeur BAC +5'],
            nouvelleValeur: '',
            nouveauBesoin: '',
        };
    },
    methods: {
        ajouterValeur() {
            if (this.nouvelleValeur.trim()) {
                this.valeurs.push(this.nouvelleValeur.trim());
                this.nouvelleValeur = '';
            }
        },
        ajouterBesoin() {
            if (this.nouveauBesoin.trim()) {
                this.besoins.push(this.nouveauBesoin.trim());
                this.nouveauBesoin = '';
            }
        },
    },
};
</script>