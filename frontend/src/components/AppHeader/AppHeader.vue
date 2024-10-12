<template>
  <q-header elevated class="py-3">
    <q-toolbar>
      <q-btn
        flat
        dense
        round
        icon="menu"
        aria-label="Menu"
        @click="$emit('toggle-drawer')"
      />
      <q-toolbar-title>
        PILIPOG YOUTH HUB
      </q-toolbar-title>
      <CustomButtonLink icon="logout" label="logout" color="white" class="py-2" :onClick="logout" />
    </q-toolbar>
  </q-header>
</template>

<script>
import { Notify } from 'quasar'
import { defineAsyncComponent } from 'vue';
import { useAuthStore } from 'src/stores/modules/authStore';

export default {
  name: 'AppHeader',
  components: {
    CustomButtonLink: defineAsyncComponent(() => import('components/Widgets/CustomButtonLink.vue')),
  },
  props: {
    version: {
      type: String,
      required: true
    }
  },
  methods: {
    async logout() {
      const authStore = useAuthStore();
      const { message, data } = await authStore.logout();

      Notify.create({
        type: 'positive',
        position: 'top',
        message: message
      })

      this.$router.push('/');
    }
  }
}
</script>

