<template>
  <div class="mb-3">
    <label :for="id" class="form-label">{{ label }}</label>
    <select
      :id="id"
      v-model="localValue"
      class="form-select form-select-lg"
      @change="$emit('update:modelValue', localValue)"
    >
      <option disabled value="">{{ placeholder }}</option>
      <option v-for="option in options" :key="option.value" :value="option.value">
        {{ option.label }}
      </option>
    </select>
  </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: String,
  options: {
    type: Array as () => { value: string; label: string }[],
    required: true,
  },
  label: String,
  placeholder: String,
  id: String,
})

const emit = defineEmits(['update:modelValue'])

const localValue = ref(props.modelValue)

watch(() => props.modelValue, (newVal) => {
  localValue.value = newVal
})
</script>
<style scoped>
.mb-3 {
  margin-bottom: 1rem;
}

.form-label {
  font-weight: 600;
  color: #222222; 
  font-size: 1rem;
  display: block;
  margin-bottom: 0.4rem;
}

.form-select {
  width: 100%;
  padding: 0.5rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  color: #212529; 
  background-color: #f8f9fa; 
  background-clip: padding-box;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
  transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
  box-shadow: none;
}

.form-select:focus {
  border-color: #0d6efd; 
  outline: 0;
  box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  background-color: #fff;
  color: #212529;
}

.form-select option {
  color: #212529;
  background-color: white;
}

.form-select option[disabled] {
  color: #6c757d; 
}


.form-select-lg {
  font-size: 1.125rem;
  padding: 0.75rem 1rem;
  border-radius: 0.5rem;
}
</style>

