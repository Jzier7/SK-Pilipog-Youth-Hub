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
    separator: false
  },
  {
    icon: 'campaign',
    label: 'Announcement',
    separator: false
  },
  {
    icon: 'forum',
    label: 'Forum',
    separator: false
  },
  {
    icon: 'event',
    label: 'Event',
    separator: true
  },
  {
    icon: 'assignment_ind',
    label: 'SK Official',
    separator: false
  },
  {
    icon: 'badge',
    label: 'Merit Board',
    separator: false
  },
  {
    icon: 'recent_actors',
    label: 'User Registry',
    separator: true
  },
  {
    icon: 'person',
    label: 'My Account',
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

