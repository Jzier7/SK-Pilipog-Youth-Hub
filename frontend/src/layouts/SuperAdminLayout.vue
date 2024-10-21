<template>
  <q-layout view="hHh lpr lfr" class="bg-grey-1">
    <AppHeader :version="$q.version" @toggle-drawer="toggleLeftDrawer" />
    <SideNav :drawerOpen="leftDrawerOpen" :menuList="menuList" @update:drawerOpen="leftDrawerOpen = $event" />

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
import { defineComponent, defineAsyncComponent } from 'vue';

const menuList = [
  {
    icon: 'dashboard',
    label: 'Dashboard',
    path: '/superadmin/dashboard',
    separator: false
  },
  {
    icon: 'campaign',
    label: 'Announcement',
    path: '/superadmin/announcement',
    separator: false
  },
  {
    icon: 'forum',
    label: 'Forum',
    path: '/superadmin/forum',
    separator: false
  },
  {
    icon: 'event',
    label: 'Event',
    path: '/superadmin/event',
    separator: true
  },
  {
    icon: 'assignment_ind',
    label: 'SK Official',
    path: '/superadmin/sk-official',
    separator: false
  },
  {
    icon: 'badge',
    label: 'Merit Board',
    path: '/superadmin/merit-board',
    separator: false
  },
  {
    icon: 'recent_actors',
    label: 'User Registry',
    path: '/superadmin/user-registry',
    separator: true
  },
  {
    icon: 'manage_accounts',
    label: 'Admin Account',
    path: '/superadmin/admin-accounts',
    separator: false
  },
  {
    icon: 'manage_accounts',
    label: 'User Account',
    path: '/superadmin/user-accounts',
    separator: false
  },
]

export default defineComponent({
  name: 'SuperAdminLayout',
  components: {
    AppHeader: defineAsyncComponent(() => import('components/AppHeader/AppHeader.vue')),
    SideNav: defineAsyncComponent(() => import('components/SideNav/SideNav.vue')),
  },
  data() {
    return {
      leftDrawerOpen: false,
      menuList
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    }
  }
});
</script>

