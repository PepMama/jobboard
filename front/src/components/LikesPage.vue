<template>
  <div class="p-4">
    <h2 class="mb-4 fw-bold">Mes offres likées</h2>

    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
      <div v-for="like in likes" :key="like.id" class="col">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">titre de l'offre</h5>
            <p class="card-text">description de l'offre</p>
            <small class="text-muted">date du like</small>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'

const likes = ref([])

onMounted(async () => {})
</script>
