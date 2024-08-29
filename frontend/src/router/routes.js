const routes = [
  {
    path: '/',
    component: () => import('pages/Auth/SignIn.vue'),
  },

  {
    path: '/signup',
    component: () => import('pages/Auth/SignUp.vue'),
  },

  {
    path: '/forgot-password',
    component: () => import('pages/Auth/ForgotPassword.vue'),
  },

  // User Routes
  {
    path: '/user',
    component: () => import('layouts/UserLayout.vue'),
    children: [
      { path: 'home', component: () => import('pages/User/UserHome.vue') }
    ]
  },

  // Admin Routes
  {
    path: '/admin',
    component: () => import('layouts/AdminLayout.vue'),
    children: [
      { path: 'dashboard', component: () => import('pages/Admin/AdminDashboard.vue') }
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
