import SignIn from 'pages/Auth/SignIn.vue'

// User Pages
import User from 'layouts/UserLayout.vue'
import Home from 'pages/User/Home.vue'

// Admin Pages
import Admin from 'layouts/AdminLayout.vue'
import Dashboard from 'pages/Admin/AdminDashboard.vue'

const routes = [

  {
    path: '/',
    component: SignIn,
  },

  // User Routes
  {
    path: '/user',
    component: User,
    children: [
      { path: '/home', component: Home }
    ]
  },

  // Admin Routes
  {
    path: '/admin',
    component: Admin,
    children: [
      { path: '/dashboard', component: Dashboard }
    ]
  },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
