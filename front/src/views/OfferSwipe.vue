<template>
  <div class="p-6">
    <input
      type="text"
      v-model="searchKeyword"
      placeholder="Rechercher un poste (ex: développeur)"
      class="border p-2 rounded w-full mb-4"
      @input="fetchOffers"
    />
    <input
      type="text"
      v-model="cityFilter"
      placeholder="Filtrer par ville (ex: Paris)"
      class="border p-2 rounded w-full mb-4"
      @input="fetchOffers"
    />

    <!-- Message si aucune offre -->
    <div v-if="filteredOffers.length === 0" class="text-center mt-10">
      Aucune offre trouvée.
    </div>

    <!-- Affichege des cards avec les offres -->
    <div v-else class="relative h-[500px] w-full">
      <Tinder ref="tinder" @swipe="handleSwipe">
        <TinderCard :key="filteredOffers[0].id" class="absolute w-full h-full">
          <div class="bg-white p-6 rounded-2xl shadow-lg h-full flex flex-col items-center justify-center text-center">
            <img
              v-if="filteredOffers[0].company?.logo"
              :src="filteredOffers[0].company.logo"
              alt="logo"
              class="h-16 mb-4"
            />
            <h2 class="text-2xl font-bold mb-2">{{ filteredOffers[0].title }}</h2>
            <p class="text-gray-700 mb-1">{{ filteredOffers[0].contractType }} – {{ filteredOffers[0].city }}</p>
            <p class="text-gray-500">Entreprise : {{ filteredOffers[0].company?.name }}</p>
          </div>
        </TinderCard>
      </Tinder>

      <div class="mt-6 flex justify-center gap-10">
        <button @click="manualSwipe('left')" class="bg-red-500 text-white px-6 py-2 rounded-full">❌ Passer</button>
        <button @click="manualSwipe('right')" class="bg-green-500 text-white px-6 py-2 rounded-full">💚 Liker</button>
      </div>
    </div>
  </div>
</template>

<script>
import { Tinder, TinderCard } from 'vue-tinder';

export default {
  components: { Tinder, TinderCard },
  //Recherche par mots clés
  data() {
    return {
      searchKeyword: '',
      cityFilter: '',
      offers: [],
      currentIndex: 0,
    };
  },
  computed: {
    // Affichage uniquement des offres pas encore swipé
    filteredOffers() {
      return this.offers.slice(this.currentIndex);
    },
  },
  mounted() {
    this.fetchOffers();
  },
  methods: {
    // Récupère les offres depuis le back
    async fetchOffers() {
      const token = localStorage.getItem('token');
      if (!token) {
        console.error("Aucun token trouvé.");
        return;
      }

      // Prépare les paramètres de recherche
      const params = new URLSearchParams();
      if (this.searchKeyword) params.append('keyword', this.searchKeyword);
      if (this.cityFilter) params.append('city', this.cityFilter);

      try {
        const response = await fetch(`https://localhost:8000/student/offers?${params.toString()}`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        if (!response.ok) {
          const text = await response.text();
          console.error("Erreur HTTP:", response.status, text);
          throw new Error(`Erreur ${response.status}`);
        }

        const data = await response.json();
        this.offers = data;
        this.currentIndex = 0;
      } catch (error) {
        console.error("Erreur chargement :", error.message);
      }
    },

    // Quand l'utilisateur swipe une carte en tactile
    async handleSwipe(direction) {
      const offer = this.filteredOffers[0];
      if (!offer) return;
      // Si swipe à droite, l'offre est liké
      if (direction === 'right') {
        await this.likeOffer(offer.id);
      }
      //Pzassage à l'offre suivante
      this.currentIndex += 1;
    },

    // Quand l'utilisateur swipe une carte via les boutons
    async manualSwipe(direction) {
      const tinder = this.$refs.tinder;
      if (tinder?.swipe) {
        await tinder.swipe(direction);
      } else {
        // Exécuter le swipe manuellement en changeant la direction de la carte
        this.handleSwipe(direction); 
      }
    },

    //Requête pour liker l'offre
    async likeOffer(offerId) {
      const token = localStorage.getItem('token');
      if (!token) return;

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
          console.error('Erreur like :', response.status, text);
        }
      } catch (error) {
        console.error('Erreur réseau like :', error.message);
      }
    },
  },
};
</script>

<style scoped>

</style>
