<template>
    <div class="homePage">
      <!-- Header -->
      <header class="d-flex justify-content-between  p-3 bg-light">
        <h1 class="login-title">AltMatch</h1>
        <div class="buttons">
          <button class="btn btn-outline-primary me-2">Login</button>
          <button class="btn btn-primary">Sign In</button>          
        </div>
      </header>
      
  <!-- Match section -->
  <section class="p-5 bg-light">
        <h2 class="text-center mb-4">Trouvez votre match</h2>
  
        <!-- Étudiants -->
        <div class="mb-5">
          <h4>Entreprises, trouvez votre alternants!</h4>
          <div class="row">
            <div class="col-md-4 mb-3" v-for="(student, i) in students" :key="i">
              <div class="card text-center match-card p-3">
                <h5>{{ student.name }}</h5>
                <p>{{ student.description }}</p>
        <div class="logo" :style="`background-image: url(${logo}); background-size: cover;`"></div>

                <button class="voirProfil">Voir le profil</button>
              </div>
            </div>
          </div>
        </div>
  
        <!-- Entreprises -->
        <div>
          <h4>Etudiants, trouvez votre entreprise!</h4>
          <div class="row">
            <div class="col-md-4 mb-3" v-for="(company, i) in companies" :key="i">
              <div class="card text-center match-card p-3">
                <h5>{{ company.name }}</h5>
                <p>{{ company.description }}</p>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Entreprises section -->
      <section class="p-5 bg-white">
        <h2 class="text-center mb-4">Les entreprises qui nous ont rejoint</h2>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-4 col-lg-2 mb-4" v-for="n in 6" :key="n">
              <div class="card text-center company-card p-3">
                <img :src="`/logo${n}.png`" alt="Entreprise Logo" class="img-fluid mb-2">
                <h5>Entreprise {{ n }}</h5>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Secteurs d'activités -->
      <section class="p-5 bg-white">
        <h2 class="text-center mb-4">Secteurs d'activités</h2>
        <div class="container">
          <div class="row justify-content-center">
            <div class="col-md-4 col-lg-2 mb-4" v-for="(sector, i) in sectors" :key="i">
              <div class="card text-center sector-card p-3">
                <h5>{{ sector }}</h5>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script >
  import logo from '../assets/logo.PNG'

  export default {
    data() {
      return {
        students: [
          { name: 'Hugo', description: "4ème année Master Ingénierie Web, recherche alternance développeur web, localisation: Lille et alentours" },
          { name: 'Emma', description: "M1 UX Design, disponible pour une alternance dans le design web, Paris et banlieue" },
          { name: 'Lucas', description: "Master Data, analyste junior, Bordeaux" }
        ],
        companies: [
          { name: 'WebCorp', description: "Recherche alternant web designer, M1/M2, durée: 1 à 2 ans, secteur: Lille" },
          { name: 'TechSoft', description: "Développeur front-end, 6 à 12 mois, Paris" },
          { name: 'DataGenius', description: "Data analyst en alternance, Lyon, 1 an" }
        ],
        sectors: ['Informatique', 'Finance', 'Commerce', 'Industrie', 'Médicale', 'Marketing']
      };
    }
  };
  </script>
  
  <style scoped src="@/CSS/home.css"></style>
  