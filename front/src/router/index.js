import { createRouter, createWebHistory } from 'vue-router'
import AuthForm from '../components/RegisterForm.vue'
import LoginForm from '../components/loginForm.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'register',
      component: AuthForm,
    },
    {
      path: '/about',
      name: 'about',
      // route level code-splitting
      // this generates a separate chunk (About.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import('../views/AboutView.vue'),
    },
    {
      path: '/logIn',
      name: 'logIn',
      component: LoginForm,
    },
  ],
})

export default router
