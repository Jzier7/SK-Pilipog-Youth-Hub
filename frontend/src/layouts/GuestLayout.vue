<template>
  <q-layout view="hHh Lpr lff" class="bg-grey-1">
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
    icon: 'home',
    label: 'Home',
    path: '/guest/home',
    separator: false
  },
  {
    icon: 'forum',
    label: 'Forum',
    path: '/guest/forum',
    separator: false
  },
  {
    icon: 'event',
    label: 'Event',
    path: '/guest/event',
    separator: true
  },
  {
    icon: 'assignment_ind',
    label: 'SK Official',
    path: '/guest/sk-official',
    separator: false
  },
  {
    icon: 'badge',
    label: 'Merit Board',
    path: '/guest/merit-board',
    separator: false
  },
]

export default defineComponent({
  name: 'GuestLayout',
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
