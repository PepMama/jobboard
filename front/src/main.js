import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

// Import Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import Vue2TouchEvents from 'vue2-touch-events'

// Vue.use(BootstrapVue)

const app = createApp(App)

const pinia = createPinia()
app.use(pinia)
app.use(router)
app.use(Vue2TouchEvents)

app.mount('#app')

// Déconnexion auto après 5 min d'inactivité
let logoutTimer
const INACTIVITY_LIMIT = 30 * 60 * 1000

function resetTimer() {
  clearTimeout(logoutTimer)
  logoutTimer = setTimeout(() => {
    localStorage.removeItem('token')
    window.location.href = '/'
  }, INACTIVITY_LIMIT)
}

;['click', 'mousemove', 'keydown'].forEach((event) => {
  window.addEventListener(event, resetTimer)
})

resetTimer()

console.log('JS chargé !');
