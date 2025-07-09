<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm">
    <div class="card-body d-flex align-items-center">
      <img :src="avatar" alt="Profil" class="rounded-circle me-3" width="80" height="80" />
      <h5 class="mb-0">{{ name }}</h5>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{
  name?: string
  avatar: string
}>()
</script>
