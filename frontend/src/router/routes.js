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
      { path: 'home', component: () => import('pages/User/UserHome.vue') },
      { path: 'forum', component: () => import('pages/Public/PublicForum.vue') },
      { path: 'event', component: () => import('pages/Public/PublicEvent.vue') },
      { path: 'sk-official', component: () => import('pages/Public/SKOfficial.vue') },
      { path: 'merit-board', component: () => import('pages/Public/MeritBoard.vue') },
      { path: 'my-account', component: () => import('pages/User/MyAccount.vue') },
    ]
  },

  // Admin Routes
  {
    path: '/admin',
    component: () => import('layouts/AdminLayout.vue'),
    children: [
      { path: 'dashboard', component: () => import('pages/Admin/AdminDashboard.vue') },
      { path: 'announcement', component: () => import('pages/Admin/Announcement.vue') },
      { path: 'forum', component: () => import('pages/Public/Forum.vue') },
      { path: 'event', component: () => import('pages/Public/Event.vue') },
      { path: 'sk-official', component: () => import('pages/Public/SKOfficial.vue') },
      { path: 'merit-board', component: () => import('pages/Public/MeritBoard.vue') },
      { path: 'user-registry', component: () => import('pages/Admin/UserRegistry.vue') },
      { path: 'my-account', component: () => import('pages/User/MyAccount.vue') },
    ]
  },

  // SuperAdmin Routes
  {
    path: '/superadmin',
    component: () => import('layouts/SuperAdminLayout.vue'),
    children: [
      { path: 'dashboard', component: () => import('pages/Admin/AdminDashboard.vue') },
      { path: 'announcement', component: () => import('pages/Admin/Announcement.vue') },
      { path: 'forum', component: () => import('pages/Public/Forum.vue') },
      { path: 'event', component: () => import('pages/Public/Event.vue') },
      { path: 'sk-official', component: () => import('pages/Public/SKOfficial.vue') },
      { path: 'merit-board', component: () => import('pages/Public/MeritBoard.vue') },
      { path: 'user-registry', component: () => import('pages/SuperAdmin/UserRegistry.vue') },
      { path: 'admin-accounts', component: () => import('pages/SuperAdmin/AdminAccounts.vue') },
      { path: 'user-accounts', component: () => import('pages/SuperAdmin/UserAccounts.vue') },
    ]
  },

  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/Public/Error/ErrorNotFound.vue')
  }
]

export default routes
