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

    <UserChatBox :unreadMessages="unreadMessages" />
  </q-layout>
</template>

<script>
import { defineComponent, defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';

const menuList = [
  {
    icon: 'home',
    label: 'Home',
    path: '/user/home',
    separator: false
  },
  {
    icon: 'forum',
    label: 'Forum',
    path: '/user/forum',
    separator: false
  },
  {
    icon: 'event',
    label: 'Event',
    path: '/user/event',
    separator: true
  },
  {
    icon: 'assignment_ind',
    label: 'SK Official',
    path: '/user/sk-official',
    separator: false
  },
  {
    icon: 'badge',
    label: 'Merit Board',
    path: '/user/merit-board',
    separator: true
  },
  {
    icon: 'person',
    label: 'My Account',
    path: '/user/my-account',
    separator: false
  },
]

export default defineComponent({
  name: 'UserLayout',
  components: {
    AppHeader: defineAsyncComponent(() => import('components/AppHeader/AppHeader.vue')),
    SideNav: defineAsyncComponent(() => import('components/SideNav/SideNav.vue')),
    UserChatBox: defineAsyncComponent(() => import('components/ChatBox/UserChatBox.vue')),
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

