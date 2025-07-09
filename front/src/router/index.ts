import { createRouter, createWebHistory } from 'vue-router'
import AuthForm from '../views/RegisterForm.vue'
import ResetPasswordForm from '../views/ResetPasswordForm.vue'
import Home from '../views/Home.vue'
import LoginForm from '../views/LoginForm.vue'
import ManageStudent from '../views/ManageStudent.vue'
import ManageCompany from '@/views/ManageCompany.vue'
import CreateOffer from '@/components/Company/CreateOffer.vue'
import OfferSwipe from '@/views/OfferSwipe.vue'
import { useAuthStore } from '@/stores/auth'
import PublicCompany from '@/views/PublicCompany.vue'
import PublicStudent from '@/views/PublicStudent.vue'

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
      meta: { requiresAuth: true, allowedRoles: ['student'] }
    },
    {
      path: '/dashboard/company',
      name: 'createCompany',
      component: ManageCompany,
      meta: { requiresAuth: true, allowedRoles: ['company'] }
    },
    {
      path: '/company/create-offer',
      name: 'createOffer',
      component: CreateOffer,
      meta: { requiresAuth: true, allowedRoles: ['company'] }
    },
    {
      path: '/dashboard/student/offers',
      name: 'showOffers',
      component: OfferSwipe,
      meta: { requiresAuth: true, allowedRoles: ['student'] }
    },
    {
      path: '/company/:name',
      name: 'PublicCompany',
      component: PublicCompany
    },
    {
      path: '/student/:name',
      name: 'PublicStudent',
      component: PublicStudent
    }
  ],
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();
  authStore.loadFromStorage();

  const isAuthenticated = !!authStore.token;
  const userRole = (authStore.role ?? '').replace('ROLE_', '').toLowerCase();

  const requiresAuth = to.meta.requiresAuth;
  const allowedRoles = to.meta.allowedRoles as string[] | undefined;

  if (requiresAuth && !isAuthenticated) {
    return next({ name: 'home' });
  }

  if (requiresAuth && allowedRoles && (!userRole || !allowedRoles.includes(userRole))) {
    return next({ name: 'home' });
  }

  next();
});

export default router
