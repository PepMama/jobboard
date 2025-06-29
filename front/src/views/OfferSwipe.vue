<template>
  <div class="p-6">
    <input
      type="text"
      v-model="searchKeyword"
      placeholder="Rechercher un poste (ex: développeur, infirmier...)"
      class="border p-2 rounded w-full mb-4"
      @input="fetchOffers"
    />

    <input
      type="text"
      v-model="cityFilter"
      placeholder="Filtrer par ville (ex: Paris, Lyon...)"
      class="border p-2 rounded w-full mb-4"
      @input="fetchOffers"
    />

    <div v-if="offers.length === 0">Aucune offre trouvée.</div>

    <div v-else class="space-y-4">
      <div
        v-for="offer in offers"
        :key="offer.id"
        class="p-4 border rounded shadow"
      >
        <img :src="offer.company.logo" alt="logo" class="h-10 mb-2" />
        <h2 class="text-xl font-bold">{{ offer.title }}</h2>
        <p>{{ offer.contractType }} – {{ offer.city }}</p>
        <p class="text-gray-500">Entreprise : {{ offer.company.name }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchKeyword: '',
      cityFilter: '',
      offers: []
    };
  },
  mounted() {
    this.fetchOffers();
  },
  methods: {
    async fetchOffers() {
    const params = new URLSearchParams();

    if (this.searchKeyword) {
        params.append('keyword', this.searchKeyword);
    }
    if (this.cityFilter) {
        params.append('city', this.cityFilter);
    }

    const response = await fetch(`https://localhost:8000/student/offers?${params.toString()}`);
    this.offers = await response.json();
    }

  }
};
</script>

<style scoped>

</style>
