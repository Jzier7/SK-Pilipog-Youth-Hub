import { guest } from 'src/middleware/guest';
import { user } from 'src/middleware/user';
import { admin } from 'src/middleware/admin';
import { superadmin } from 'src/middleware/superadmin';

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

  // Guest Routes
  {
    path: '/guest',
    component: () => import('layouts/GuestLayout.vue'),
    meta: { middlewares: [guest] },
    children: [
      { path: 'home', component: () => import('pages/User/UserHome.vue') },
      { path: 'forum', component: () => import('pages/Public/PublicForum/PublicForum.vue') },
      { path: 'event', component: () => import('pages/Public/PublicEvent.vue') },
      { path: 'sk-official', component: () => import('pages/Public/SKOfficial.vue') },
      { path: 'merit-board', component: () => import('pages/Public/MeritBoard.vue') },
    ]
  },

  // User Routes
  {
    path: '/user',
    component: () => import('layouts/UserLayout.vue'),
    meta: { middlewares: [user] },
    children: [
      { path: 'home', component: () => import('pages/User/UserHome.vue') },
      { path: 'forum', component: () => import('pages/Public/PublicForum/PublicForum.vue') },
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
    meta: { middlewares: [admin] },
    children: [
      { path: 'dashboard', component: () => import('pages/Admin/AdminDashboard.vue') },
      { path: 'announcement', component: () => import('pages/Admin/AdminAnnouncement.vue') },
      { path: 'forum', component: () => import('pages/Public/PublicForum/PublicForum.vue') },
      { path: 'event', component: () => import('pages/Admin/Event/MainPage.vue') },
      { path: 'sk-official', component: () => import('pages/Admin/SKOfficial/MainPage.vue') },
      { path: 'merit-board', component: () => import('pages/Public/MeritBoard.vue') },
      { path: 'user-registry', component: () => import('pages/Admin/UserRegistry.vue') },
      { path: 'my-account', component: () => import('pages/User/MyAccount.vue') },
    ]
  },

  // SuperAdmin Routes
  {
    path: '/superadmin',
    component: () => import('layouts/SuperAdminLayout.vue'),
    meta: { middlewares: [superadmin] },
    children: [
      { path: 'dashboard', component: () => import('pages/Admin/AdminDashboard.vue') },
      { path: 'announcement', component: () => import('pages/Admin/AdminAnnouncement.vue') },
      { path: 'forum', component: () => import('pages/Public/PublicForum/PublicForum.vue') },
      { path: 'event', component: () => import('pages/Admin/Event/MainPage.vue') },
      { path: 'sk-official', component: () => import('pages/Admin/SKOfficial/MainPage.vue') },
      { path: 'merit-board', component: () => import('pages/Public/MeritBoard.vue') },
      { path: 'user-registry', component: () => import('pages/Admin/UserRegistry.vue') },
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
