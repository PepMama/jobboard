import { createRouter, createWebHistory } from 'vue-router'
import AuthForm from '../components/RegisterForm.vue'
import ResetPasswordForm from '../components/ResetPasswordForm.vue'
import Home from '../components/Home.vue'
import LoginForm from '../components/LoginForm.vue'
import ManageStudent from '../views/ManageStudent.vue'
import ManageEntreprise from '../views/ManageEntreprise.vue'
import ManageCompany from '@/views/ManageCompany.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/register',
      name: 'register',
      component: AuthForm,
    },
    {
      path: '/logIn',
      name: 'logIn',
      component: LoginForm,
    },
    {
      path: '/resetPassword',
      name: 'resetPassword',
      component: ResetPasswordForm,
    },
    {
      path: '/dashboard/student',
      name: 'createStudent',
      component: ManageStudent,
    },
    {
      path: '/dashboard/company',
      name: 'createCompany',
      component: ManageCompany
    }
  ],
})

export default router
