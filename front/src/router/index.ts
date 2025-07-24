import { createRouter, createWebHistory } from 'vue-router'
import AuthForm from '../views/RegisterForm.vue'
import ResetPasswordForm from '../views/ResetPasswordForm.vue'
import ForgotPassword from '../views/ForgotPassword.vue'
import Home from '../views/Home.vue'
import LoginForm from '../views/LoginForm.vue'
import ManageStudent from '../views/ManageStudent.vue'
import ManageCompany from '@/views/ManageCompany.vue'
import CreateOffer from '@/components/Company/CreateOffer.vue'
import EditOffer from '@/components/Company/EditOffer.vue'
import OffersList from '@/components/Company/OffersList.vue'
import OfferSwipe from '@/views/OfferSwipe.vue'
import CompanyLikes from '@/views/CompanyLikes.vue'
import StudentLikes from '@/views/StudentLikes.vue'
import { useAuthStore } from '@/stores/auth'
import PublicCompany from '@/views/PublicCompany.vue'
import PublicStudent from '@/views/PublicStudent.vue'
import Candidates from '@/views/StudentSwipe.vue'
import StudentMatches from '@/components/Students/StudentMatches.vue'
import CompanyMatches from '@/components/Company/CompanyMatches.vue'
import OfferDetails from '@/views/OfferDetails.vue'
import { ref } from 'vue'

const profileChecked = ref(false)
const profileComplete = ref(true)

async function checkProfile(role: string) {
  const token = localStorage.getItem('token')
  if (!token) return false
  let url = ''
  if (role === 'student') url = `${import.meta.env.VITE_API_URL}/student/profile`
  else if (role === 'company') url = `${import.meta.env.VITE_API_URL}/company/profile`
  else return true
  try {
    const res = await fetch(url, { headers: { Authorization: `Bearer ${token}` } })
    if (!res.ok || res.status === 204) return false
    const data = await res.json()
    if (role === 'student') {
      return !!(data.firstname && data.name && data.city && data.description)
    } else {
      return !!(data.name && data.city && data.description)
    }
  } catch {
    return false
  }
}

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
      path: '/reset-password',
      name: 'resetPassword',
      component: ResetPasswordForm,
    },
    {
      path: '/forgot-password',
      name: 'forgotPassword',
      component: ForgotPassword,
    },
    {
      path: '/dashboard/student',
      name: 'createStudent',
      component: ManageStudent,
      meta: { requiresAuth: true, allowedRoles: ['student'] },
    },
    {
      path: '/dashboard/company',
      name: 'createCompany',
      component: ManageCompany,
      meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/company/create-offer',
      name: 'createOffer',
      component: CreateOffer,
      meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/company/edit-offer/:id',
      name: 'editOffer',
      component: EditOffer,
      meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/company/offers',
      name: 'companyOffers',
      component: OffersList,
      meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/dashboard/student/offers',
      name: 'showOffers',
      component: OfferSwipe,
      meta: { requiresAuth: true, allowedRoles: ['student'] },
    },
    {
      path: '/company/:name',
      name: 'PublicCompany',
      component: PublicCompany,
      // meta: { requiresAuth: true, allowedRoles: ['student'] },
    },
    {
      path: '/student/:name',
      name: 'PublicStudent',
      component: PublicStudent,
      // meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/dashboard/company/candidates',
      name: 'CandidatesSwipe',
      component: Candidates,
      meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/dashboard/student/matches',
      name: 'StudentMatches',
      component: StudentMatches,
      meta: { requiresAuth: true, allowedRoles: ['student'] },
    },
    {
      path: '/dashboard/company/matches',
      name: 'CompanyMatches',
      component: CompanyMatches,
      meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/company/likes',
      name: 'CompanyLikes',
      component: CompanyLikes,
      meta: { requiresAuth: true, allowedRoles: ['company'] },
    },
    {
      path: '/dashboard/student/likes',
      name: 'StudentLikes',
      component: StudentLikes,
      meta: { requiresAuth: true, allowedRoles: ['student'] },
    },
    {
      path: '/offer/:id',
      name: 'OfferDetails',
      component: OfferDetails,
      meta: { requiresAuth: true },
    },
  ],
})

router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  authStore.loadFromStorage()

  const isAuthenticated = !!authStore.token
  const userRole = (authStore.role ?? '').replace('ROLE_', '').toLowerCase()

  const requiresAuth = to.meta.requiresAuth
  const allowedRoles = to.meta.allowedRoles as string[] | undefined

  if (requiresAuth && !isAuthenticated) {
    return next({ name: 'home' })
  }

  if (requiresAuth && allowedRoles && (!userRole || !allowedRoles.includes(userRole))) {
    return next({ name: 'home' })
  }

  // Bloque la navigation si le profil n'est pas complété (hors page de profil)
  if (requiresAuth && !['/dashboard/student', '/dashboard/company'].includes(to.path)) {
    const complete = await checkProfile(userRole)
    if (!complete) {
      return next({ path: userRole === 'student' ? '/dashboard/student' : '/dashboard/company', query: { incomplete: '1' } })
    }
  }

  next()
})

export default router
