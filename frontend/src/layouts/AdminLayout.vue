<template>
  <q-layout view="hHh Lpr lff" class="bg-grey-1">
    <AppHeader :version="$q.version" @toggle-drawer="toggleLeftDrawer" />
    <SideNav :drawerOpen="leftDrawerOpen" :menuList="menuList" @update:drawerOpen="leftDrawerOpen = $event" />

    <q-page-container>
      <router-view />
    </q-page-container>

    <q-page-sticky position="bottom-right" :offset="[18, 18]" v-if="!modalStore.showChatBox">
      <q-btn fab icon="chat" color="secondary" @click="toggleChatBox">
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
    separator: false
  },
  {
    icon: 'place',
    label: 'Purok',
    path: '/admin/purok',
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
