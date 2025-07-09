<template>
  <div class="popup-backdrop" @click.self="$emit('close')">
    <div class="popup">
      <button @click="$emit('close')" class="btn-close"></button>
      <h1>{{ offer.company?.name }}</h1>
      <h2>{{ offer.title }}</h2>
      <p><strong>Description :</strong> <br />{{ offer.description }}</p>
      <p><strong>Contrat :</strong> {{ offer.contractType }}</p>
      <p><strong>Salaire :</strong> {{ offer.salary ?? 'Non précisé' }}</p>
      <p><strong>Ville :</strong> {{ offer.city ?? 'Non précisé' }}</p>
      <p><strong>Télétravail :</strong> {{ offer.remote ? 'Oui' : 'Non' }}</p>
      <p><strong>Date de début :</strong> {{ formatDate(offer.startDate) }}</p>
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
h1 {
  font-weight: 700;
}
.popup-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999;
}

.popup {
  background: white;
  padding: 2rem;
  border-radius: 1rem;
  max-width: 1000px;
  width: 100%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
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
  right: 30rem;
}
</style>
