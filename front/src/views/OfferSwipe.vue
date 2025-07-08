<template>
  <div class="offer-swipe-container">
    <!-- Filtres -->
    <div class="filters">
      <input
        v-model="searchKeyword"
        @input="fetchOffers"
        placeholder="Poste"
        class="filter-input"
      />
      <input
        v-model="cityFilter"
        @input="fetchOffers"
        placeholder="Ville"
        class="filter-input"
      />
    </div>

    <!-- Carrousel -->
    <div class="carousel">
      <div
        v-for="(offer, index) in visibleCards"
        :key="offer.id"
        class="card"
        :class="{
          'card-center': index === 1,
          'card-side': index !== 1,
        }"
      >
        <img v-if="offer.company?.logo" :src="offer.company.logo" class="company-logo" />
        <h2 class="offer-title">{{ offer.title }}</h2>
        <p class="offer-info">{{ offer.contractType }} – {{ offer.city }}</p>
        <p class="offer-company">Entreprise : {{ offer.company?.name }}</p>

        <div v-if="index === 1" class="card-buttons">
          <button @click="swipeLeft" class="btn-swipe-left">❌ Passer</button>
          <button @click="swipeRight" class="btn-swipe-right">💚 Liker</button>
        </div>
      </div>
    </div>

    <!-- Message si vide -->
    <div v-if="offers.length === 0" class="empty-message">
      Aucune offre trouvée.
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      offers: [],
      currentIndex: 0,
      searchKeyword: '',
      cityFilter: '',
    };
  },
  computed: {
    visibleCards() {
      const prev = this.offers[this.currentIndex - 1] || {};
      const current = this.offers[this.currentIndex] || {};
      const next = this.offers[this.currentIndex + 1] || {};
      return [prev, current, next];
    },
  },
  mounted() {
    this.fetchOffers();
  },
  methods: {
    async fetchOffers() {
      const token = localStorage.getItem('token');
      if (!token) {
        console.error("Token manquant.");
        return;
      }

      const params = new URLSearchParams();
      if (this.searchKeyword) params.append('keyword', this.searchKeyword);
      if (this.cityFilter) params.append('city', this.cityFilter);

      try {
        const response = await fetch(`https://localhost:8000/student/offers?${params.toString()}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        if (!response.ok) throw new Error(`Erreur ${response.status}`);
        const data = await response.json();
        this.offers = data;
        this.currentIndex = 0;
      } catch (err) {
        console.error("Erreur chargement :", err.message);
      }
    },
    async swipeRight() {
      const currentOffer = this.offers[this.currentIndex];
      if (currentOffer) await this.likeOffer(currentOffer.id);
      this.currentIndex++;
    },
    swipeLeft() {
      this.currentIndex++;
    },
    async likeOffer(offerId) {
      const token = localStorage.getItem('token');
      try {
        const response = await fetch(`https://localhost:8000/student/like-offer/${offerId}`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        });
        if (!response.ok) {
          const text = await response.text();
          console.error("Erreur like :", response.status, text);
        }
      } catch (err) {
        console.error("Erreur réseau like :", err.message);
      }
    },
  },
};
</script>

<style scoped>
/* Tu peux maintenant styliser ici librement */

.offer-swipe-container {
  background-color: #f5f5f5;
  min-height: 100vh;
  padding: 2rem;
}
input::placeholder{
    font-size: 15px;
}

.filters {
  display: flex;
  gap: 1rem;
  margin-bottom: 2rem;
}

.filter-input {
  flex: 1;
  padding: 0.5rem;
  background-color: #f0f0f0;
  border: none ;
  border-radius: 6px;
  font-size: 0.9rem;
}

.carousel {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1.5rem;
  margin-top: 10%;
}

.card {
  width: 30rem;
  height: 30rem;
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 2px 6px rgba(0,0,0,0.1);
  padding: 1rem;
  display: flex;
  flex-direction: column;
  text-align: center;
  transition: transform 0.3s ease, opacity 0.3s ease;
}

.card-center {
  transform: scale(1);
  opacity: 1;
  z-index: 2;
  box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.card-side {
  transform: scale(0.9);
  opacity: 0.6;
  z-index: 1;
}

.company-logo {
  height: 48px;
  margin-bottom: 0.5rem;
}

.offer-title {
  font-weight: bold;
  font-size: 1.1rem;
}

.offer-info {
  font-size: 0.9rem;
  color: #555;
}

.offer-company {
  font-size: 0.8rem;
  color: #888;
  margin-top: 0.5rem;
}

.card-buttons {
  margin-top: auto;
  display: flex;
  justify-content: center;
  gap: 1rem;
  padding-top: 1rem;
}

.btn-swipe-left,
.btn-swipe-right {
  border: none;
  padding: 0.4rem 1rem;
  border-radius: 999px;
  font-size: 0.8rem;
  cursor: pointer;
}

.btn-swipe-left {
  background-color: #e3342f;
  color: white;
}

.btn-swipe-right {
  background-color: #38c172;
  color: white;
}

.empty-message {
  margin-top: 2rem;
  text-align: center;
  color: #777;
}
</style>

