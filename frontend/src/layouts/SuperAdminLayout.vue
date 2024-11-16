<template>
  <q-layout view="hHh Lpr lff" class="bg-grey-1">
    <AppHeader :version="$q.version" @toggle-drawer="toggleLeftDrawer" />
    <SideNav :drawerOpen="leftDrawerOpen" :menuList="menuList" @update:drawerOpen="leftDrawerOpen = $event" />

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-page-sticky position="bottom-right" :offset="[18, 18]" v-if="!modalStore.showChatBox">
      <q-btn fab icon="chat" color="secondary" @click="toggleChatBox">
        <q-badge v-if="unreadMessages > 0" color="negative" floating label="!" />
      </q-btn>
    </q-page-sticky>

    <AdminChatBox :unreadMessages="unreadMessages" />
  </q-layout>
</template>

<script>
import { defineComponent, defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';

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
    AdminChatBox: defineAsyncComponent(() => import('components/ChatBox/AdminChatBox.vue')),
  },
  data() {
    return {
      leftDrawerOpen: false,
      menuList,
      unreadMessages: 5,
      modalStore: useModalStore()
    };
  },
  methods: {
    toggleLeftDrawer() {
      this.leftDrawerOpen = !this.leftDrawerOpen;
    },
    updateUnreadMessages(count) {
      this.unreadMessages = count;
    },
    toggleChatBox() {
      const modalStore = useModalStore();
      modalStore.setShowChatBox(true);
    },
  }
});
</script>

