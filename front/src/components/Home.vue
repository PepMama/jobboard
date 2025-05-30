<template>
  <div class="homepage">
    <header class="header">
      <h1 class="title">AltMatch</h1>
      <div class="logo" :style="{ backgroundImage: `url(${logo})` }"></div>
      <div class="button-group">
        <button class="btn-outline">Connexion</button>
        <button class="btn-filled">Inscription</button>
      </div>
    </header>

    <!-- Match section -->
    <section class="section-match">
      <h2 class="section-title">Trouvez votre match</h2>

      <!-- Étudiants -->
      <div class="students">
        <h4 class="section-subtitle">Entreprises, trouvez votre alternants!</h4>
        <div class="student-list">
          <div class="student-card" v-for="(student, i) in students" :key="i">
            <h5 class="student-name">{{ student.name }}</h5>
            <p class="student-desc">{{ student.description }}</p>
            <button class="profile-btn">Voir le profil</button>
          </div>
        </div>
      </div>

      <!-- Entreprises -->
      <div class="companies">
        <h4 class="section-subtitle">Etudiants, trouvez votre entreprise!</h4>
        <div class="company-list">
          <div class="company-card" v-for="(company, i) in companies" :key="i">
            <h5 class="company-name">{{ company.name }}</h5>
            <p class="company-desc">{{ company.description }}</p>
            <button class="profile-btn">Voir l'offre</button>
          </div>
        </div>
      </div>
    </section>

    <!-- Entreprises section -->
    <section class="section-joined">
      <h2 class="section-title">Les entreprises qui nous ont rejoint</h2>
      <div class="joined-list">
        <div class="joined-card" v-for="n in 6" :key="n">
          <img :src="`/logo${n}.png`" alt="Entreprise Logo" class="joined-img">
          <h5 class="joined-name">Entreprise {{ n }}</h5>
        </div>
      </div>
    </section>

    <!-- Secteurs d'activités -->
    <section class="section-sectors">
      <h2 class="section-title">Secteurs d'activités</h2>
      <div class="sector-list">
        <div class="sector-card" v-for="(sector, i) in sectors" :key="i">
          <h5 class="sector-name">{{ sector }}</h5>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import logo from '../assets/logo.png';
import profilPic from '../assets/profilfemme1.webp';

export default {
  data() {
    return {
      students: [
        { name: 'Hugo', description: "4ème année Master Ingénierie Web, recherche alternance développeur web, localisation: Lille et alentours" },
        { name: 'Emma', description: "M1 UX Design, disponible pour une alternance dans le design web, Paris et banlieue" },
        { name: 'Lucas', description: "Master Data, analyste junior, Bordeaux" }
      ],
      companies: [
        { name: 'WebCorp', description: "Web designer, M1/M2, 1 à 2 ans, Lille" },
        { name: 'TechSoft', description: "Développeur front-end, 6 à 12 mois, Paris" },
        { name: 'DataGenius', description: "Data analyst en alternance, Lyon, 1 an" }
      ],
      sectors: ['Informatique', 'Finance', 'Commerce', 'Industrie', 'Médicale', 'Marketing']
    };
  }
};
</script>

<style src="@/CSS/home.css"></style>

  