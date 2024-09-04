<template>
  <q-layout view="hHh lpr lfr">
    <AppHeader :version="$q.version" @toggle-drawer="toggleLeftDrawer" />
    <SideNav :links="linksList" :drawerOpen="leftDrawerOpen" :menuList="menuList" @update:drawerOpen="leftDrawerOpen = $event" />

    <q-page-container>
      <router-view />
    </q-page-container>

  </q-layout>
</template>

<script>
import { defineComponent, ref, defineAsyncComponent } from 'vue';

const menuList = [
  {
    icon: 'dashboard',
    label: 'Dashboard',
    path: '/admin/dashboard',
    separator: false
  },
  {
    icon: 'campaign',
    label: 'Announcement',
    path: '/admin/announcement',
    separator: false
  },
  {
    icon: 'forum',
    label: 'Forum',
    path: '/admin/forum',
    separator: false
  },
  {
    icon: 'event',
    label: 'Event',
    path: '/admin/event',
    separator: true
  },
  {
    icon: 'assignment_ind',
    label: 'SK Official',
    path: '/admin/sk-official',
    separator: false
  },
  {
    icon: 'badge',
    label: 'Merit Board',
    path: '/admin/merit-board',
    separator: false
  },
  {
    icon: 'recent_actors',
    label: 'User Registry',
    path: '/admin/user-registry',
    separator: true
  },
  {
    icon: 'person',
    label: 'My Account',
    path: '/admin/my-account',
    separator: false
  },
]

export default defineComponent({
  name: 'AdminLayout',
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

