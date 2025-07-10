<template>
  <div class="card bg-white text-dark rounded-3 shadow-sm mt-3">
    <div class="card-body">
      <h5>Informations personnelles</h5>

      <template v-if="isPublic">
        <div
          v-for="(label, key) in personalFields"
          :key="key"
          class="mb-3"
        >
          <label class="form-label fw-bold text-dark d-block">
            {{ label }}
          </label>
          <p class="text-muted mb-0" style="white-space: pre-line">
            {{ form[key] || '—' }}
          </p>
        </div>
      </template>

      <template v-else>
        <form @submit.prevent="onSubmit">
          <div
            v-for="(label, key) in personalFields"
            :key="key"
            class="mb-3"
          >
            <label class="form-label text-dark">{{ label }}</label>

            <template v-if="key === 'description'">
              <textarea
                v-model="form[key]"
                class="form-control"
              ></textarea>
            </template>
            <template v-else>
              <input
                type="text"
                v-model="form[key]"
                class="form-control"
              />
            </template>
          </div>

          <button class="btn btn-outline-primary" type="submit">
            Mettre à jour
          </button>
        </form>
      </template>
    </div>
  </div>
</template>