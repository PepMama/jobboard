import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: (): {
    token: string | null
    role: string | null
    user: string | null
  } => ({
    token: null,
    role: null,
    user: null,
  }),
  actions: {
    setAuth(token: string, role: string) {
      this.token = token
      this.role = role
      localStorage.setItem('token', token)
      localStorage.setItem('role', role)
    },
    reset() {
      this.token = null
      this.role = null
      this.user = null
    },
    clearAuth() {
      this.token = null
      this.role = null
      localStorage.removeItem('token')
      localStorage.removeItem('role')
    },
    loadFromStorage() {
      this.token = localStorage.getItem('token')
      this.role = localStorage.getItem('role')
    },
  },
})
