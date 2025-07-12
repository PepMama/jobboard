<template>
  <div class="offer-swipe-layout">
    <Sidebar />
    <div class="main-content">
      <PageHeader title="Offres" @toggle-sidebar="showSidebar = true" />
      <h1>Trouve ton alternance sur-mesure</h1>
      <!-- Filtres -->
      <div class="filters">
        <div class="input-with-icon">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="icon"
            width="20"
            height="20"
            viewBox="0 0 24 24"
            fill="none"
            stroke="black"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          <input
            v-model="searchKeyword"
            @input="fetchOffers"
            placeholder="mot clé (ex: développeur, infirmier...)"
            class="filter-input"
          />
        </div>

        <div class="input-with-icon">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            class="icon"
            width="20"
            height="20"
            viewBox="0 0 24 24"
            fill="none"
            stroke="black"
            stroke-width="2"
            stroke-linecap="round"
            stroke-linejoin="round"
          >
            <path d="M21 10c0 6-9 13-9 13S3 16 3 10a9 9 0 1 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
          <input
            v-model="cityFilter"
            @input="fetchOffers"
            placeholder="Ville"
            class="filter-input"
          />
        </div>
      </div>

      <!-- Carrousel -->
      <div class="carousel">
        <div
          v-for="(offer, index) in visibleCards"
          :key="offer.id || index"
          class="card"
          :class="{
            'card-center': offer.id === offers[currentIndex]?.id,
            'card-side': offer.id !== offers[currentIndex]?.id,
          }"
        >
          <div v-if="offer.id === offers[currentIndex]?.id" class="card-buttons">
            <div class="view-offer-container">
              <button @click="openPopup(offer)" class="btn-view-offer">Voir détails</button>
            </div>
          </div>
          <img v-if="offer.company?.logo" :src="offer.company.logo" class="company-logo" />
          <h2 class="offer-title">{{ offer.title }}</h2>
          <h3 class="offer-company">{{ offer.company?.name }}</h3>
          <div class="offer-details">
            <p><span class="icon-circle">📍</span> {{ offer.city || 'Ville inconnue' }}</p>
            <p>
              <span class="icon-circle">🏠</span> {{ offer.remote ? 'Télétravail' : 'Sur site' }}
            </p>
            <p>
              <span class="icon-circle">📅</span> Début : {{ offer.startDate || 'Non précisé' }}
            </p>
            <p>
              <span class="icon-circle">💰</span>
              {{ offer.salary ? offer.salary + '€' : 'Salaire non précisé' }}
            </p>
          </div>
        </div>
      </div>

      <div class="swipeButton">
        <button @click="swipeLeft" class="btn-swipe-left">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="#fca5a5"
            viewBox="0 0 24 24"
            width="24"
            height="24"
          >
            <path
              d="M18.3 5.71a1 1 0 0 0-1.41 0L12 10.59 7.11 5.7a1 1 0 0 0-1.41 1.41L10.59 12l-4.89 4.89a1 1 0 1 0 1.41 1.41L12 13.41l4.89 4.89a1 1 0 0 0 1.41-1.41L13.41 12l4.89-4.89a1 1 0 0 0 0-1.4z"
            />
          </svg>
          Passer
        </button>
        <button @click="swipeRight" class="btn-swipe-right">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="#86efac"
            viewBox="0 0 24 24"
            width="24"
            height="24"
          >
            <path
              d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                    2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                    C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5
                    c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
            />
          </svg>
          Postuler
        </button>
      </div>

      <!-- Message si la section est vide -->
      <div v-if="offers.length === 0" class="empty-message">Aucune offre trouvée.</div>
    </div>
    <OfferPopup v-if="showPopup" :offer="selectedOffer" @close="closePopup" />
  </div>
</template>

<script>
import Sidebar from '@/components/Global/NavBar.vue'
import OfferPopup from '@/components/Company/OfferPopUp.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
export default {
  components: {
    Sidebar,
    OfferPopup,
    PageHeader
  },
  data() {
    return {
      offers: [],
      currentIndex: 0,
      searchKeyword: '',
      cityFilter: '',
      hasSwipedOnce: false,
      showPopup: false,
      selectedOffer: null,
    }
  },
  computed: {
    visibleCards() {
      const cards = []

      if (this.hasSwipedOnce && this.offers[this.currentIndex - 1]) {
        cards.push(this.offers[this.currentIndex - 1])
      }

      if (this.offers[this.currentIndex]) {
        cards.push(this.offers[this.currentIndex])
      }

      if (this.offers[this.currentIndex + 1]) {
        cards.push(this.offers[this.currentIndex + 1]) 
      }

      return cards
    },
  },
  mounted() {
    this.fetchOffers()
  },
  methods: {
    async fetchOffers() {
      const token = localStorage.getItem('token')
      if (!token) {
        console.error('Token manquant.')
        return
      }

      const params = new URLSearchParams()
      if (this.searchKeyword) params.append('keyword', this.searchKeyword)
      if (this.cityFilter) params.append('city', this.cityFilter)

      try {
        const response = await fetch(`https://localhost:8000/student/offers?${params.toString()}`, {
          headers: { Authorization: `Bearer ${token}` },
        })

        if (!response.ok) throw new Error(`Erreur ${response.status}`)
        const data = await response.json()
        this.offers = data
        this.currentIndex = 0
      } catch (err) {
        console.error('Erreur chargement :', err.message)
      }
    },
    swipeLeft() {
      if (!this.hasSwipedOnce) this.hasSwipedOnce = true
      this.currentIndex++
    },

    async swipeRight() {
      if (!this.hasSwipedOnce) this.hasSwipedOnce = true
      const currentCard = document.querySelector('.card-center')

      if (currentCard) {
        currentCard.classList.add('card-zoom-in')

        setTimeout(async () => {
          const currentOffer = this.offers[this.currentIndex]
          if (currentOffer) await this.likeOffer(currentOffer.id)
          currentCard.classList.remove('card-zoom-in')
          this.currentIndex++
        }, 400)
      }
    },
    openPopup(offer) {
      this.selectedOffer = offer
      this.showPopup = true
    },
    closePopup() {
      this.showPopup = false
    },

    async likeOffer(offerId) {
      const token = localStorage.getItem('token')
      try {
        const response = await fetch(`http://localhost:8000/student/like-offer/${offerId}`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        })
        if (!response.ok) {
          const text = await response.text()
          console.error('Erreur like :', response.status, text)
        }
      } catch (err) {
        console.error('Erreur réseau like :', err.message)
      }
    },
  },
}
</script>

<style  scoped>
h1 {
  text-align: center;
  margin-bottom: 3%;
  color: #5651abd9;
  font-weight: 600;
  font-size: 1.1rem;
}

.offer-title {
  margin-top: 2%;
  font-size: 2rem;
  font-weight: 700;
  color: #2d2d2d;
  margin-bottom: 0.2rem;
}
.offer-company {
  font-size: 1.6rem;
  font-weight: 700;
  color: #777;
  margin-top: 2%;
  margin-bottom: 1rem;
}

P {
  font-size: 150%;
}
.offer-swipe-layout {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.main-content {
  flex: 1;
  overflow-y: auto;
  padding: 2rem;
}

.sidebar {
  width: 250px;
  background-color: #fff;
  height: 100vh;
}

.offer-swipe-container {
  background-color: #f5f5f5;
  min-height: 100vh;
  padding: 2rem;
  display: flex;
}
input::placeholder {
  font-size: 15px;
  color: #777;
}

.filters {
  display: flex;
  margin: auto;
  margin-bottom: 1rem;
  border-radius: 50px;
}
.filter-input {
  padding: 10px 12px 10px 40px;
  font-size: 16px;
  border : none;
  border-radius: 50px;
}

.input-with-icon {
  position: relative;
  margin:auto;
  width: 40%;
}


.input-with-icon .icon {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  pointer-events: none;
}

.carousel {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1.5rem;
  margin-top: 3%;
}

.card {
  width: 45rem;
  height: 30rem;
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  padding: 1rem;
  display: flex;
  flex-direction: column;
  text-align: center;
  transition:
    transform 0.3s ease,
    opacity 0.3s ease;
}

.card-center {
  transform: scale(1);
  opacity: 1;
  z-index: 2;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.card-side {
  transform: scale(0.9);
  opacity: 0.6;
  z-index: 1;
}
.card-zoom-in {
  animation: zoomIn 0.4s ease-out forwards;
  z-index: 10;
}

@keyframes zoomIn {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  100% {
    transform: scale(1.3);
    opacity: 0;
  }
}

.company-logo {
  height: 48px;
  margin-bottom: 0.5rem;
}

.offer-details {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  font-size: 0.95rem;
  color: #444;
  margin-bottom: 1rem;
  text-align: left;
  align-items: start;
  padding-left: 1rem;
}

.icon-circle {
  background-color: #e0e0e0;
  color: #333;
  font-size: 0.8rem;
  border-radius: 50%;
  padding: 0.4rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.5rem;
}

.offer-info {
  font-size: 0.9rem;
  color: #555;
}

.card-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  position: absolute;
  right: 1rem;
}

.btn-swipe-left,
.btn-swipe-right {
  border: none;
  padding: 0.4rem 1rem;
  border-radius: 999px;
  font-size: 0.8rem;
  cursor: pointer;
  box-shadow: 0 10px 14px rgba(0, 0, 0, 0.1);
}

.btn-swipe-left {
  background: linear-gradient(to bottom, #f87171, #e3342f);
  color: white;
}

.btn-swipe-right {
  background: linear-gradient(to bottom, #51d88a, #38c172);
  color: white;
}

.empty-message {
  margin-top: 2rem;
  text-align: center;
  color: #777;
}
.btn-view-offer {
  margin-top: 1rem;
  background: linear-gradient(to bottom, #9e97f3, #5651ab);
  color: white;
  border: none;
  padding: 0.4rem 1rem;
  border-radius: 999px;
  font-size: 0.8rem;
  cursor: pointer;
}

.btn-view-offer:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
.swipeButton {
  display: flex;
  flex-direction: row;
  margin: auto;
  border-radius: 50%;
  width: 25%;
  margin-top: 2%;
  gap: 2rem;
}
</style>
