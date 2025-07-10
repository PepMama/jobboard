<template>
  <div class="homepage">
    <header class="header">
      <img :src="altmatch" alt="altmatch" class="altmatch" />
      <img src="../assets/logo.PNG" alt="Logo" />
      <div class="button-group">
        <router-link to="/login" class="btn-outline">Connexion</router-link>
        <router-link to="/register" class="btn-filled">Inscription</router-link>
      </div>
    </header>

    <div class="accroche">
      <div class="accroche-container">
        <img :src="accueil" alt="Image d’accueil" class="accroche-img" />
        <div class="accroche-texte">
          <span class="color">Le site qui facilite</span><br />
          <span class="color">votre recherche</span><br />
          <span class="dancingscript">d'alternan(t)ce !</span>
        </div>
      </div>
    </div>

    <!-- Match section -->
    <section class="section-match">
      <h2 class="section-title">
        Plus de 40 000 offres d'alternances <br />
        Plus de 45 000 candidats
      </h2>
      <!-- Étudiants -->
      <div class="etudiants">
        <div class="cards-section">
          <h4 class="section-subtitle">
            <span class="normal-text">Entreprises, trouvez</span><br />
            <span class="bold-text">votre alternant(e)!</span>
          </h4>
          <div class="student-list">
            <div class="student-card">
              <img :src="hugo" alt="hugo" class="joined-img" />
              <h5 class="student-name">Hugo</h5>
              <p class="student-desc">M2 Ingénierie du Web</p>
            </div>
            <div class="student-card">
              <img :src="emma" alt="emma" class="joined-img" />
              <h5 class="student-name">Emma</h5>
              <p class="student-desc">M1 Design d'interieur</p>
            </div>
            <div class="student-card">
              <img :src="lucas" alt="lucas" class="joined-img" />
              <h5 class="student-name">Lucas</h5>
              <p class="student-desc">M2 Finance</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Entreprises -->
      <div class="entreprises">
        <div class="cards-section">
          <h4 class="section-subtitle">
            <span class="normal-text">Etudiants, trouvez</span><br />
            <span class="bold-text">votre entreprise!</span>
          </h4>
          <div class="company-list">
            <div class="company-card">
              <img :src="financecorp" alt="financecorp" class="joined-img" />
              <h5 class="company-name bold-text">FinanceCorp</h5>
              <p class="company-desc">22 offres d'alternances</p>
            </div>
            <div class="company-card">
              <img :src="techsoft" alt="techsoft" class="joined-img" />
              <h5 class="company-name bold-text">TechSoft</h5>
              <p class="company-desc">15 offres d'alternances</p>
            </div>
            <div class="company-card">
              <img :src="medgenius" alt="medgenius" class="joined-img" />
              <h5 class="company-name bold-text">MedGenius</h5>
              <p class="company-desc">34 offres d'alternances</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Entreprises section -->
    <section class="section-joined">
      <h2 class="section-title">Quelques entreprises qui nous ont rejoint</h2>
      <div class="joined-list">
        <div class="joined-card">
          <img :src="nexoraLogo" alt="Entreprise Logo" class="joined-img" />
        </div>
        <div class="joined-card">
          <img :src="greenpulseLogo" alt="Entreprise Logo" class="joined-img" />
        </div>
        <div class="joined-card">
          <img :src="velisaLogo" alt="Entreprise Logo" class="joined-img" />
        </div>
        <div class="joined-card">
          <img :src="altoviaLogo" alt="Entreprise Logo" class="joined-img" />
        </div>
        <div class="joined-card">
          <img :src="lumenisLogo" alt="Entreprise Logo" class="joined-img" />
        </div>
        <div class="joined-card">
          <img :src="terraNovaLogo" alt="Entreprise Logo" class="joined-img" />
        </div>
      </div>
    </section>

    <section class="why-us">
      <div class="why-us-container">
        <!-- Partie gauche -->
        <div class="why-us-left">
          <h2 class="why-us-title">
            Pourquoi <img :src="altmatch" alt="altmatch" class="altmatchwhy" /> ?<img
              :src="arrow"
              alt="Pourquoi altMatch"
              class="arrow"
            />
          </h2>
        </div>

        <!-- Partie droite -->
        <div class="why-us-right">
          <p>
            AltMatch est la plateforme 100% dédiée à l'alternance. Elle regroupe un grand nombre
            d’offres qualifiées et de candidats motivés dans tous les secteurs. Grâce à un système
            intelligent de mise en avant basé sur les préférences des entreprises et des candidats,
            chaque profil et chaque offre a une vraie visibilité. Nous vous garantissons une
            expérience simplifiée, ciblée et efficace, pour que chaque match soit une réussite.
          </p>
        </div>
      </div>
    </section>

    <!-- Secteurs d'activités -->
    <section class="section-sectors">
      <h2 class="section-title">Retrouvez les offres et candidats par secteurs d'activités</h2>
      <div class="sector-list">
        <div class="sector-card">
          <img :src="informatique" alt="informatique icone" />
          <h5 class="sector-name bold-text">Informatique</h5>
        </div>
        <div class="sector-card">
          <img :src="finance" alt="finance icone" />
          <h5 class="sector-name bold-text">Finance</h5>
        </div>
        <div class="sector-card">
          <img :src="grossery" alt="grossery icone" />
          <h5 class="sector-name bold-text">Commerce</h5>
        </div>
        <div class="sector-card">
          <img :src="industrial" alt="industrial icone" />
          <h5 class="sector-name bold-text">Industrie</h5>
        </div>
        <div class="sector-card">
          <img :src="medical" alt="medical icone" />
          <h5 class="sector-name bold-text">Médicale</h5>
        </div>
        <div class="sector-card">
          <img :src="marketing" alt="marketing icone" />
          <h5 class="sector-name bold-text">Marketing</h5>
        </div>
      </div>
    </section>
    <footer class="footer">
      <div class="footer-container">
        <div class="footer-links">
          <router-link to="/mentions-legales">Mentions légales</router-link>
          <router-link to="/statuts">Statuts</router-link>
          <router-link to="/cookies">Cookies</router-link>
        </div>
        <div class="footer-copy">
          © AltMatch {{ new Date().getFullYear() }} - Tous droits réservés
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import nexoraLogo from '../assets/nexora.png'
import greenpulseLogo from '../assets/Greenpulse.png'
import altoviaLogo from '../assets/altovia.png'
import velisaLogo from '../assets/velisa.png'
import lumenisLogo from '../assets/lumenis.png'
import terraNovaLogo from '../assets/terraNova.png'
import hugo from '../assets/hugo.png'
import emma from '../assets/emma.png'
import lucas from '../assets/lucas.png'
import accueil from '../assets/accueil.jpg'
import altmatch from '../assets/altmatch.png'
import techsoft from '../assets/techsoft.jpg'
import financecorp from '../assets/financecorp.jpg'
import medgenius from '../assets/medgenius.jpg'
import grossery from '../assets/grossery.png'
import finance from '../assets/finance.png'
import marketing from '../assets/marketing.png'
import informatique from '../assets/informatique.png'
import medical from '../assets/medical.png'
import industrial from '../assets/industrial.png'
import arrow from '../assets/arrow.png'
</script>

<style src="@/CSS/home.css" scoped></style>
