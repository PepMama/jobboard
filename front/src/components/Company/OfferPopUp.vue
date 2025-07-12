<template>
  <div class="popup-backdrop" @click.self="$emit('close')">
    <div class="popup">
      <button @click="$emit('close')" class="btn-close"></button>
      <h1 >{{ offer.company?.name }}</h1>
      <h2>{{ offer.title }}</h2>
      <p><strong class="section-title">Description :</strong> <br />{{ offer.description }}</p>
      <p><strong class="section-title">Contrat :</strong> {{ offer.contractType }}</p>
      <p><strong class="section-title">Salaire :</strong> {{ offer.salary ?? 'Non précisé' }}</p>
      <p><strong class="section-title">Ville :</strong> {{ offer.city ?? 'Non précisé' }}</p>
      <p><strong class="section-title">Télétravail :</strong> {{ offer.remote ? 'Oui' : 'Non' }}</p>
      <p><strong class="section-title">Date de début :</strong> {{ formatDate(offer.startDate) }}</p>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    offer: Object,
  },
  methods: {
    formatDate(date) {
      const d = new Date(date)
      return d.toLocaleDateString('fr-FR')
    },
  },
}
</script>

<style scoped>
.popup-backdrop {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50;
}
.section-title {
  display: flex;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-weight: 600;
  font-size: 1rem;
  margin-bottom: 0.75rem;
  color: #64748b;
}
.popup {
  background: #fff;
  padding: 2.5rem 3rem;
  border-radius: 12px;
  max-width: 600px;
  width: 100%;
  position: relative;
  max-height: 90vh;
  overflow-y: auto;
  font-family: 'Open Sans', sans-serif;
  color: #333;
  box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}


.btn-close:hover {
  color: #000;
}

h1, h2, p {
  margin-bottom: 1rem;
}

h1 {
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  font-size: 2rem;
  color: #1e293b;
  text-align: center;
  margin-bottom: 2rem;
}

.btn-close {
  margin-top: 0.2rem;
  background-color: #e3342f;
  color: white;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  cursor: pointer;
  width: 2%;
  position: absolute;
  right: 2rem;
}
</style>
