<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm">
    <div class="card-body">
      <h5>Description</h5>
      <div v-if="edit">
        <textarea v-model="localBio" class="form-control"></textarea>
      </div>
      <p v-else>{{ bio }}</p>

      <!-- Le bouton n'apparaît que si le mode édition est activé (non readonly) -->
      <button v-if="!readonly" class="btn btn-outline-primary mt-2" @click="toggle">
        {{ edit ? 'Sauvegarder' : 'Modifier' }}
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps<{ bio: string }>()
const emit  = defineEmits(['update:bio'])

const edit     = ref(false)
const localBio = ref(props.bio)

watch(() => props.bio, v => { if (!edit.value) localBio.value = v })

function toggle () {
  if (edit.value) emit('update:bio', localBio.value)
  edit.value = !edit.value
}
</script>