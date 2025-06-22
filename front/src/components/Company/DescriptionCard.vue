<template>
    <div class="card bg-white text-dark rounded-3 shadow-sm">
      <div class="card-body">
        <h5>Description</h5>
  
        <div v-if="edit">
          <textarea v-model="localDescription" class="form-control"></textarea>
        </div>
        <p v-else>{{ description }}</p>
  
        <button class="btn btn-success mt-2" @click="toggle">
          {{ edit ? 'Sauvegarder' : 'Modifier' }}
        </button>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref, watch } from 'vue'
  
  const props = defineProps<{ description: string }>()
  const emit  = defineEmits(['update:description'])
  
  const edit     = ref(false)
  const localDescription = ref(props.description)
  
  watch(() => props.description, v => { if (!edit.value) localDescription.value = v })
  
  function toggle () {
    if (edit.value) emit('update:description', localDescription.value)
    edit.value = !edit.value
  }
  </script>
  