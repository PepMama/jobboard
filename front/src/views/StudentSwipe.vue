<template>
  <div class="offer-swipe-layout">
    <Sidebar/>
    <StudentPopup
      v-if="showPopup"
      :student="selectedStudent"
      @close="closePopup"
    />
    <div class="main-content">
      <PageHeader title="Candidats" @toggle-sidebar="showSidebar = true" />
      <h1>Trouvez votre candidat idéal</h1>

      <!-- Filtres -->
      <div class="filters">
        <div class="input-with-icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8" />
            <line x1="21" y1="21" x2="16.65" y2="16.65" />
          </svg>
          <input v-model="searchKeyword" @input="fetchStudents" placeholder="Mot-clé (ex: React, marketing...)" class="filter-input" />
        </div>

        <div class="input-with-icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 10c0 6-9 13-9 13S3 16 3 10a9 9 0 1 1 18 0z" />
            <circle cx="12" cy="10" r="3" />
          </svg>
          <input v-model="cityFilter" @input="fetchStudents" placeholder="Ville" class="filter-input" />
        </div>

        <div class="input-with-icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 7h7l3 3 3-3h7v13H2z" />
            <line x1="12" y1="10" x2="12" y2="20" />
          </svg>
          <select v-model="fieldOfStudyFilter" @change="fetchStudents" class="filter-input">
            <option value="" disabled selected>Domaine d'étude</option>
            <option value="">-- Aucun --</option>
            <option>Informatique & Numérique</option>
            <option>Santé & Médecine</option>
            <option>Commerce, Management & Marketing</option>
            <option>Ingénierie & Sciences de l’Industrie</option>
            <option>Droit</option>
            <option>Sciences Économiques & Gestion</option>
            <option>Sciences Politiques & Relations Internationales</option>
            <option>Architecture & Urbanisme</option>
            <option>Sciences Sociales & Psychologie</option>
            <option>Arts, Design & Communication Visuelle</option>
            <option>Agriculture</option>
            <option>Electromécanique</option>
          </select>
        </div>


        <div class="input-with-icon">
          <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="20" height="25" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12l-10 7-10-7 10-7 10 7z" />
            <path d="M2 12v5a10 10 0 0 0 20 0v-5" />
          </svg>
          <select v-model="degreeFilter" @change="fetchStudents" class="filter-input">
            <option value="" disabled selected>Diplôme</option>
            <option value="">-- Aucun --</option>
            <option>BAC +1</option>
            <option>BAC +2</option>
            <option>BAC +3</option>
            <option>BAC +4</option>
            <option>BAC +5</option>
          </select>
        </div>
      </div>

      <div class="carousel">
        <div v-for="(student, index) in visibleCards" :key="student.id || index" class="card" :class="{
          'card-center': student.id === students[currentIndex]?.id,
          'card-side': student.id !== students[currentIndex]?.id,
           'card-zoom-in': index === zoomIndex
        }">
          <div v-if="student.id === students[currentIndex]?.id" class="card-buttons">
            <div class="view-offer-container">
              <button @click="openPopup(student)" class="btn-view-offer">Voir détails</button>
            </div>
          </div>
          <h2 class="offer-title">{{ student.firstName }} {{ student.lastName }}</h2>
          <h3 class="offer-company">{{ student.city || 'Ville inconnue' }}</h3>
          <div class="offer-details">
            <p><span class="icon-circle">🎓</span> {{ student.educations[0]?.degree || 'Diplôme inconnu' }}</p>
            <p><span class="icon-circle">📘</span> {{ student.educations[0]?.fieldOfStudy || 'Domaine inconnu' }}</p>
            <p><span class="icon-circle">📝</span> {{ student.city || 'Pas de description' }}</p>
          </div>
          <div v-if="student.CV" class="cv-btn-container">
            <a :href="`https://localhost:8000/uploads/cvs/${student.CV}`" target="_blank" class="btn-cv">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-file-text" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
              CV
            </a>
          </div>
        </div>
      </div>
      <div class="swipeButton">
        <button @click="swipeLeft" class="btn-swipe-left">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="#fca5a5"
            viewBox="0 0 24 24"
            width="24"
            height="24"
          >
            <path
              d="M18.3 5.71a1 1 0 0 0-1.41 0L12 10.59 7.11 5.7a1 1 0 0 0-1.41 1.41L10.59 12l-4.89 4.89a1 1 0 1 0 1.41 1.41L12 13.41l4.89 4.89a1 1 0 0 0 1.41-1.41L13.41 12l4.89-4.89a1 1 0 0 0 0-1.4z"
            />
          </svg>
          Passer
        </button>
        <button @click="swipeRight" class="btn-swipe-right">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="#86efac"
            viewBox="0 0 24 24"
            width="24"
            height="24"
          >
            <path
              d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5
                    2 5.42 4.42 3 7.5 3c1.74 0 3.41 0.81 4.5 2.09
                    C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5
                    c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"
            />
          </svg>
          Liker
        </button>
      </div>
      <div v-if="students.length === 0" class="empty-message">Aucun candidat trouvé.</div>
    </div>
  </div>
</template>

<script>
import Sidebar from '@/components/Global/NavBar.vue'
import PageHeader from '@/components/Global/PageHeader.vue'
import StudentPopup from '@/components/Students/StudentPopUp.vue'


export default {
  components: { Sidebar, PageHeader, StudentPopup },
  data() {
    return {
      students: [],
      currentIndex: 0,
      searchKeyword: '',
      cityFilter: '',
      fieldOfStudyFilter: '',
      degreeFilter: '',
      hasSwipedOnce: false,
      showPopup: false,
      selectedStudent: null,
      zoomIndex: null,
    }
  },
  computed: {
    visibleCards() {
      const cards = []
      if (this.hasSwipedOnce && this.students[this.currentIndex - 1]) {
        cards.push(this.students[this.currentIndex - 1])
      }
      if (this.students[this.currentIndex]) {
        cards.push(this.students[this.currentIndex])
      }
      if (this.students[this.currentIndex + 1]) {
        cards.push(this.students[this.currentIndex + 1])
      }
      return cards
    }
  },
  mounted() {
    this.fetchStudents()
  },
  methods: {
    triggerZoom(index) {
    this.zoomIndex = index;
    setTimeout(() => {
      this.zoomIndex = null;  
    }, 500); 
  },
    async fetchStudents() {
      const token = localStorage.getItem('token')
      if (!token) {
        console.error('Token manquant.')
        return
      }

       const params = new URLSearchParams();
        if (this.searchKeyword) params.append('keyword', this.searchKeyword);
        if (this.cityFilter) params.append('city', this.cityFilter);
        if (this.degreeFilter) params.append('degree', this.degreeFilter);
        if (this.fieldOfStudyFilter) params.append('fieldOfStudy', this.fieldOfStudyFilter);

      try {
        const response = await fetch(`https://localhost:8000/company/students?${params.toString()}`, {
          headers: { Authorization: `Bearer ${token}` },
        })

        if (!response.ok) throw new Error(`Erreur ${response.status}`)
        const data = await response.json()
        this.students = data;
        this.currentIndex = 0;
      } catch (err) {
        console.error('Erreur chargement :', err.message)
      }
    },
    swipeLeft() {
      if (!this.hasSwipedOnce) this.hasSwipedOnce = true
      this.currentIndex++
    },
    async swipeRight() {
      if (!this.hasSwipedOnce) this.hasSwipedOnce = true
      const currentStudent = this.students[this.currentIndex]
      if (currentStudent) {
        await this.contactStudent(currentStudent.id)
         this.triggerZoom(1);
        this.currentIndex++
      }
    },
    async contactStudent(studentId) {
      const token = localStorage.getItem('token')
      try {
        const response = await fetch(`https://localhost:8000/company/contact-student/${studentId}`, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json'
          }
        })
        if (!response.ok) {
          const text = await response.text()
          console.error('Erreur contact :', response.status, text)
        }
      } catch (err) {
        console.error('Erreur réseau contact :', err.message)
      }
    },
    openPopup(student) {
      this.selectedStudent = student
      this.showPopup = true
    },
    closePopup() {
      this.showPopup = false
    }
  }
}
</script>

<style  scoped>
h1 {
  text-align: center;
  margin-bottom: 3%;
  color: #5a189a ;
  font-weight: 600;
  font-size: 1.1rem;
}

.offer-title {
  margin-top: 4rem;
  font-size: 2rem;
  font-weight: 700;
  color: #2d2d2d;
  margin-bottom: 0.2rem;
}
.offer-company {
  font-size: 1.6rem;
  font-weight: 700;
  color: #777;
  margin-top: 2%;
  margin-bottom: 1rem;
}

P {
  font-size: 150%;
}
.offer-swipe-layout {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

.main-content {
  flex: 1;
  overflow-y: auto;
  padding: 2rem;
}

.sidebar {
  width: 250px;
  background-color: #fff;
  height: 100vh;
}

.offer-swipe-container {
  background-color: #f5f5f5;
  min-height: 100vh;
  padding: 2rem;
  display: flex;
}
input::placeholder {
  font-size: 15px;
  color: #777;
}

.filters {
  display: flex;
  gap: 1rem;
  margin: auto;
  margin-bottom: 1rem;
  border-radius: 50px;
}

.filter-input {
  width: 100%;
  padding: 8px 12px 8px 36px; 
  font-size: 1rem;
  border: 1px solid #ccc;
  border : none;
  border-radius: 50px;

  background-color: #fff;
 appearance: none; 
  -webkit-appearance: none;
  -moz-appearance: none;
  cursor: pointer;
  transition: border-color 0.2s ease-in-out;
} 

.input-with-icon {
  position: relative;
  margin:auto;
  width: 40%;
  min-width: 200px;
  max-width: 400px;
}
.input-with-icon select {
  background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg fill='none' stroke='%23333' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M6 9l6 6 6-6'%3E%3C/path%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
  background-size: 16px 16px;
  padding-right: 36px; 
}
 .filter-input:focus {
  border-color: #0d6efd; 
  box-shadow: 0 0 8px rgba(13, 110, 253, 0.5);
}

.input-with-icon .icon {
  position: absolute;
  top: 50%;
  left: 12px;
  transform: translateY(-50%);
  pointer-events: none;
}


.carousel {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 1.5rem;
  margin-top: 3%;
}

.card {
  width: 45rem;
  height: 30rem;
  background-color: white;
  border-radius: 1rem;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  padding: 1rem;
  display: flex;
  flex-direction: column;
  text-align: center;
  transition:
    transform 0.3s ease,
    opacity 0.3s ease;
}

.card-center {
  transform: scale(1);
  opacity: 1;
  z-index: 2;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
}

.card-side {
  transform: scale(0.9);
  opacity: 0.6;
  z-index: 1;
}
.card-zoom-in {
  animation: zoomIn 0.4s ease-out forwards;
  z-index: 10;
}

@keyframes zoomIn {
  0% {
    transform: scale(1);
    opacity: 1;
  }
  100% {
    transform: scale(1.3);
    opacity: 0;
  }
}

.company-logo {
  height: 48px;
  margin-bottom: 0.5rem;
}

.offer-details {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  font-size: 0.95rem;
  color: #444;
  margin-bottom: 1rem;
  text-align: left;
  align-items: start;
  padding-left: 1rem;
}

.icon-circle {
  background-color: #e0e0e0;
  color: #333;
  font-size: 0.8rem;
  border-radius: 50%;
  padding: 0.4rem;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  margin-right: 0.5rem;
}

.offer-info {
  font-size: 0.9rem;
  color: #555;
}

.card-buttons {
  display: flex;
  justify-content: center;
  gap: 1rem;
  position: absolute;
  right: 1rem;
}

.btn-swipe-left,
.btn-swipe-right {
  border: none;
  padding: 0.4rem 1rem;
  border-radius: 999px;
  font-size: 0.8rem;
  cursor: pointer;
  box-shadow: 0 10px 14px rgba(0, 0, 0, 0.1);
}

.btn-swipe-left {
  background: linear-gradient(to bottom, #f87171, #e3342f);
  color: white;
}

.btn-swipe-right {
  background: linear-gradient(to bottom, #51d88a, #38c172);
  color: white;
}

.empty-message {
  margin-top: 2rem;
  text-align: center;
  color: #777;
}
.btn-view-offer {
  margin-top: 1rem;
  background: linear-gradient(to bottom, #9e97f3, #5a189a );
  color: white;
  border: none;
  padding: 0.4rem 1rem;
  border-radius: 999px;
  font-size: 0.8rem;
  cursor: pointer;
}

.btn-view-offer:hover {
  transform: translateY(-1px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}
.swipeButton {
  display: flex;
  flex-direction: row;
  margin: auto;
  border-radius: 50%;
  width: 25%;
  margin-top: 2%;
  gap: 2rem;
}

.cv-btn-container {
  display: flex;
  justify-content: center;
  margin-top: 1.5rem;
}
.btn-cv {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  background: linear-gradient(to bottom, #51d88a, #38c172);
  color: white;
  font-weight: 600;
  border: none;
  border-radius: 999px;
  padding: 0.6rem 1.5rem;
  font-size: 1rem;
  text-decoration: none;
  transition: background 0.2s, box-shadow 0.2s;
  box-shadow: 0 4px 12px rgba(56, 193, 114, 0.15);
}
.btn-cv:hover {
  background: linear-gradient(to bottom, #38c172, #51d88a);
  color: #fff;
  box-shadow: 0 8px 24px rgba(56, 193, 114, 0.25);
}

@media (max-width: 1024px) {
  .carousel {
    flex-direction: row;
    flex-wrap: nowrap;
    gap: 1rem;
  }

  .card {
    width: 22rem;
    height: 26rem;
  }

  .input-with-icon {
    width: 45%;
  }

  .filters {
    flex-wrap: wrap;
    justify-content: center;
  }

  .swipeButton {
    width: 60%;
    justify-content: center;
    gap: 1.5rem;
  }
  .offer-details {
    
    font-size: 0.6rem;
  }
  .offer-title {
    margin-top: 4rem;
    font-size: 1rem;
  }

   .btn-view-offer {
    width: 100%;
    height: auto;
    padding: 0.7rem;
    margin: 0;
    font-size: 0.4rem;
  }
}

@media (max-width: 768px) {

   .btn-view-offer {
    width: 100%;
    font-size: 0.7rem;
    height: auto;
    padding: 0.7rem;
    margin: 0;
  }

  .offer-swipe-layout {
    flex-direction: column;
  }

  .sidebar {
    display: none;
  }

  .main-content {
    padding: 1rem;
  }

  .filters {
    flex-direction: column;
    gap: 0.75rem;
  }

  .input-with-icon {
    width: 100%;
  }

  .carousel {
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }

  .card {
    width: 90vw;
    height: auto;
  }

  .card-side {
    display: none !important;
  }

  .swipeButton {
    flex-direction: column;
    width: 90%;
    margin-top: 1rem;
    gap: 0.75rem;
    justify-content: center;
    align-items: center;
  }

  .btn-swipe-left,
  .btn-swipe-right {
    width: 100%;
    font-size: 1rem;
  }
}

@media (max-width: 480px) {
  h1 {
    font-size: 1rem;
  }

  .offer-title {
    font-size: 1.4rem;
  }

  .offer-company {
    font-size: 1.2rem;
  }

  .offer-details {
    font-size: 0.9rem;
  }


  .btn-view-offer {
    width: 90%;
    font-size: 0.9rem;
    height: auto;
    padding: 0.7rem;
    margin: 0;
    font-size: 0.5rem;
  }
}

</style>