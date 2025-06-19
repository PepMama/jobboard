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
                        <!-- profil -->
                        <div class="card bg-white text-dark rounded-3 shadow-sm">
                            <div class="card-body d-flex align-items-center">
                                <img src="https://resize.elle.fr/portrait_1280/var/plain_site/storage/images/loisirs/series/de-gossip-girl-a-you-5-choses-que-vous-ignoriez-sur-penn-badgley-3829250/92305759-1-fre-FR/De-Gossip-Girl-a-You-5-choses-que-vous-ignoriez-sur-Penn-Badgley.jpg"
                                    alt="Profil" class="rounded-circle me-3" width="80" height="80" />
                                <h5 class="mb-0">Nom d'utilisateur</h5>
                            </div>
                        </div>

                        <!-- infos -->
                        <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
                            <div class="card-body">
                                <h5>Informations personnelles</h5>
                                <form @submit.prevent="submitForm">
                                    <div class="mb-3">
                                        <label class="form-label text-dark">Prénom :</label>
                                        <input type="text" class="form-control" v-model="firstname"/>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-dark">Nom :</label>
                                        <input type="text" class="form-control" v-model="name" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label text-dark">N° de téléphone :</label>
                                        <input type="text" class="form-control" v-model="phone"/>
                                    </div>
                                    <div class="mb-3">
                                      <label class="form-label text-dark">Votre âge : </label>
                                      <input type="text" class="form-control" v-model="age"/>
                                    </div>
                                    <div class="mb-3">
                                      <label class="form-label text-dark">Adresse : </label>
                                      <input type="text" class="form-control" v-model="address"/>
                                    </div>
                                    <div class="mb-3">
                                      <label class="form-label text-dark">Ville : </label>
                                      <input type="text" class="form-control" v-model="city"/>
                                    </div>                                    <div class="mb-3">
                                    <label class="form-label text-dark">Code postal : </label>
                                    <input type="text" class="form-control" v-model="postal_code"/>
                                  </div>
                                    <div class="mb-3">
                                      <label class="form-label text-dark">Petite description : </label>
                                      <textarea type="text" class="form-control" v-model="description"/>
                                    </div>
                                    <button type="submit" class="btn btn-success">Mettre à jour</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Colonne droite -->
                    <div class="flex-fill" style="min-width: 300px; max-width: 30%;">
                        <div class="card bg-white text-dark rounded-3 shadow-sm">
                            <div class="card-body">
                                <h5>Bio</h5>
                                <div v-if="isEditBio">
                                    <textarea v-model="bio" class="form-control"></textarea>
                                </div>
                                <div v-else>
                                    <p>{{ bio }}</p>
                                </div>
                                <button @click="toggleEditionBio" class="btn btn-success mt-2">
                                    {{ isEditBio ? 'Sauvegarder' : 'Modifier' }}
                                </button>

                            </div>
                        </div>

                        <!-- langues -->
                        <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
                            <div class="card-body">
                                <h5>Langues</h5>
                                <ul class="list-group list-group-flush">
                                    <li v-for="(langue, index) in langues" :key="index"
                                        class="list-group-item shadow-sm rounded-3 text-dark mb-2"
                                        style="background-color: #d4edda;">
                                        {{ langue }}
                                    </li>

                                </ul>
                                <button class="btn btn-success mt-3" data-bs-toggle="modal"
                                    data-bs-target="#langueModal">
                                    Ajouter une langue
                                </button>
                            </div>
                        </div>

                        <!-- expériences -->
                        <div class="card bg-white text-dark rounded-3 shadow-lm mt-3">
                            <div class="card-body">
                                <h5>Expériences</h5>
                                <ul class="list-group list-group-flush">
                                    <li v-for="(experience, index) in experiences" :key="index"
                                        class="list-group-item shadow-sm rounded-3 text-dark mb-2"
                                        style="background-color: #d4edda;">
                                        {{ experience }}
                                    </li>
                                </ul>
                                <button class="btn btn-success mt-3" data-bs-toggle="modal"
                                    data-bs-target="#experienceModal">
                                    Ajouter une expérience
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- modale langue -->
                <div class="modal fade" id="langueModal" tabindex="-1" aria-labelledby="langueModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="langueModalLabel">Ajouter une langue</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <input v-model="nouvelleLangue" type="text" class="form-control"
                                    placeholder="Ex: Français" />
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-success" @click="ajouterLangue"
                                    data-bs-dismiss="modal">
                                    Ajouter
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- modale experience -->
                <div class="modal fade" id="experienceModal" tabindex="-1" aria-labelledby="experienceModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title" id="experienceModalLabel">Ajouter une experience</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body">
                                <input v-model="nouvelleExperience" type="text" class="form-control"
                                    placeholder="Ex: Français" />
                            </div>
                            <div class="modal-footer d-flex justify-content-between">
                                <button type="button" class="btn btn-success" @click="ajouterExperience"
                                    data-bs-dismiss="modal">
                                    Ajouter
                                </button>
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
        firstname: '',
        name: '',
        phone: '',
        age: '',
        address: '',
        city: '',
        postal_code: '',
        bio: "Ma bio",
        isEditBio: false,
        langues: ['français'],
        experiences: [
          'Développeur Frontend chez Altazion',
          'Stage en développement web'
        ],
        nouvelleLangue: '',
        nouvelleExperience: ''
      };
    },
    methods: {
        ajouterLangue() {
            if (this.nouvelleLangue.trim()) {
                this.langues.push(this.nouvelleLangue.trim());
                this.nouvelleLangue = '';
            }
        },
        ajouterExperience() {
            if (this.nouvelleExperience.trim()) {
                this.experiences.push(this.nouvelleExperience.trim());
                this.nouvelleExperience = '';
            }
        },
        toggleEditionBio() {
            this.isEditBio = !this.isEditBio;
        },
      async submitForm() {
        const token = localStorage.getItem('token');

        const payload = {
          firstname: this.firstname,
          name: this.name,
          phone_number: this.phone,
          age: this.age,
          address: this.address,
          city: this.city,
          postal_code: this.postal_code,
          description: this.description,
          photo: null,
          linkedin: null,
          github: null,
          cv: null
        };

        console.log("Payload envoyé :", payload);

        try {
          const response = await fetch('http://localhost:8000/student/profile', {
            method: 'PUT',
            headers: {
              'Content-Type': 'application/json',
              'Authorization': `Bearer ${token}`,
            },
            body: JSON.stringify(payload),
          });

          const data = await response.json();

          if (!response.ok) {
            console.error('Erreur:', data.error);
          } else {
            alert('Profil mis à jour avec succès !');
          }

        } catch (error) {
          console.error('Erreur réseau:', error);
        }
      }
    },
};
</script>
