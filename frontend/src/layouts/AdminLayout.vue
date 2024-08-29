<template>
  <q-layout view="hHh lpr lfr">
    <AppHeader :version="$q.version" @toggle-drawer="toggleLeftDrawer" />
    <SideNav :links="linksList" :drawerOpen="leftDrawerOpen" @update:drawerOpen="leftDrawerOpen = $event" />

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineComponent, ref, defineAsyncComponent } from 'vue';

export default defineComponent({
  name: 'UserLayout',
  components: {
    AppHeader: defineAsyncComponent(() => import('components/AppHeader/AppHeader.vue')),
    SideNav: defineAsyncComponent(() => import('components/SideNav/SideNav.vue')),
  },
  data() {
    return {
      leftDrawerOpen: false
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    }
  }
});
</script>

