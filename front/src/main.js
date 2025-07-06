import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'


import App from './App.vue'
import router from './router/index.js'

// Import Bootstrap CSS
import 'bootstrap/dist/css/bootstrap.min.css'
import 'bootstrap/dist/js/bootstrap.bundle.min.js'

// Vue.use(BootstrapVue)


const app = createApp(App)

const pinia = createPinia()
app.use(pinia)
app.use(router)

app.mount('#app')

// Déconnexion auto après 5 min d'inactivité
let logoutTimer;
const INACTIVITY_LIMIT = 100 * 60 * 1000;

function resetTimer() {
  clearTimeout(logoutTimer);
  logoutTimer = setTimeout(() => {
    localStorage.removeItem('token');
    window.location.href = '/';
  }, INACTIVITY_LIMIT);
}

['click', 'mousemove', 'keydown'].forEach(event => {
  window.addEventListener(event, resetTimer);
});

resetTimer();

