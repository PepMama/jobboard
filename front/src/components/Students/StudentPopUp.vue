<template>
  <div class="popup-backdrop" @click.self="$emit('close')">
    <div class="popup">
      <button @click="$emit('close')" class="btn-close" aria-label="Fermer la popup">✕</button>

      <h1 class="title">{{ student.firstName }} {{ student.lastName }}</h1>

      <section class="section description">
        <h2 class="section-title">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 20h9"></path>
            <path d="M12 4h9"></path>
            <path d="M4 9h16"></path>
            <path d="M4 15h16"></path>
          </svg>
          Description
        </h2>
        <p>{{ student.description }}</p>
      </section>

      <section v-if="student.skills && student.skills.length" class="section skills">
        <h2 class="section-title">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
            stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <path d="M14.31 8l5.74 9.94"></path>
            <path d="M9.69 8h11.48"></path>
            <path d="M7.38 12l5.74-9.94"></path>
            <path d="M9.69 16L3.95 6.06"></path>
            <path d="M14.31 16H2.83"></path>
            <path d="M16.62 12l-5.74 9.94"></path>
          </svg>
          Compétences
        </h2>
        <ul class="skills-list">
          <li v-for="skill in student.skills" :key="skill" :class="['badge', getColorClass(skill)]">
            {{ skill }}
          </li>
        </ul>
      </section>

      <section class="section links">
        <h2 class="section-title">
          <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M10 14a5 5 0 0 1 7 0l3 3"></path>
            <path d="M14 10a5 5 0 0 0-7 0l-3 3"></path>
          </svg>
          Liens
        </h2>
        <ul class="links-list">
          <li v-if="student.LinkedIn">
            <a :href="student.LinkedIn" target="_blank" rel="noopener noreferrer" class="link">LinkedIn</a>
          </li>
          <li v-if="student.Github">
            <a :href="student.Github" target="_blank" rel="noopener noreferrer" class="link">GitHub</a>
          </li>
          <li v-if="student.portfolio">
            <a :href="student.portfolio" target="_blank" rel="noopener noreferrer" class="link">Portfolio</a>
          </li>
          <li v-if="student.CV">
            <a :href="`https://localhost:8000/uploads/cvs/${student.CV}`" target="_blank" rel="noopener noreferrer" class="link">
              <svg style="vertical-align:middle;margin-right:4px;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              CV
            </a>
          </li>
        </ul>
      </section>

      <router-link
        :to="`/student/${student.id}`"
        class="btn-profile"
      >
        Voir le profil complet
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    student: Object,
  },
  methods: {
    getCvUrl(fileName) {
      return `https://localhost:8000/uploads/cv/${fileName}`;
    },
    getColorClass(skillName) {
      if (!skillName) return 'badge-gray';
      const colors = [
        'badge-blue',
        'badge-green',
        'badge-purple',
        'badge-pink',
        'badge-yellow',
      ];
      const index = skillName.length % colors.length;
      return colors[index];
    },
  },
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@600&family=Open+Sans&display=swap');

.popup-backdrop {
  position: fixed;
  inset: 0;
  background-color: rgba(0, 0, 0, 0.6);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 50;
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

.btn-close {
  position: absolute;
  top: 1rem;
  right: 1rem;
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  color: #666;
  transition: color 0.2s ease;
}
.btn-close:hover {
  color: #000;
}

.title {
  font-family: 'Montserrat', sans-serif;
  font-weight: 700;
  font-size: 2.2rem;
  margin-bottom: 3rem;
  color: #1e293b;
  text-align: center;

}

.section {
  margin-bottom: 2rem;
}

.section-title {
  display: flex;
  align-items: center;
  font-family: 'Montserrat', sans-serif;
  font-weight: 600;
  font-size: 1.5rem;
  margin-bottom: 0.75rem;
  color: #64748b;
}

.section-title .icon {
  width: 24px;
  height: 24px;
  margin-right: 0.5rem;
  stroke: #64748b;
}

.skills-list {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.badge {
  padding: 0.3rem 0.8rem;
  border-radius: 9999px;
  font-size: 0.85rem;
  font-weight: 600;
  color: white;
  user-select: none;
  cursor: default;
  transition: transform 0.2s ease;
}
.badge:hover {
  transform: scale(1.1);
}

.badge-blue {
  background-color: #3b82f6; 
}
.badge-green {
  background-color: #10b981; 
}
.badge-purple {
  background-color: #8b5cf6; 
}
.badge-pink {
  background-color: #ec4899; 
}
.badge-yellow {
  background-color: #eab308; 
}
.badge-gray {
  background-color: #6b7280; 
}

.links-list {
  list-style-type: disc;
  padding-left: 1.25rem;
}

.link {
  color: #2563eb;
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s ease;
}
.link:hover {
  color: #1e40af;
  text-decoration: none;
}

.btn-download {
  background-color: #2563eb;
  color: white;
  padding: 0.6rem 1.2rem;
  border-radius: 0.5rem;
  text-decoration: none;
  font-weight: 600;
  display: inline-block;
  transition: background-color 0.2s ease;
}
.btn-download:hover {
  background-color: #1e40af;
}

.btn-profile {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.75rem 1.5rem;
  background-color: #5a189a ; 
  color: white;
  border-radius: 0.5rem;
  font-weight: 700;
  text-align: center;
  text-decoration: none;
  transition: background-color 0.2s ease;
}
.btn-profile:hover {
  background-color: #431372 ;
}
</style>
