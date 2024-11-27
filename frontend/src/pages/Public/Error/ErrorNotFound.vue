<template>
  <div class="fullscreen text-primary text-center q-pa-md flex flex-center">
    <div>
      <div style="font-size: 30vh">
        404
      </div>

      <div class="text-h2" style="opacity:.4">
        Oops. Nothing here...
      </div>

      <q-btn class="q-mt-xl" color="white" text-color="primary" unelevated label="Close" :onClick="logout" no-caps />
    </div>
  </div>
</template>

<script>
import { useAuthStore } from 'src/stores/modules/authStore';
import { defineComponent } from 'vue';

export default defineComponent({
  name: 'ErrorNotFound',
  methods: {
    async logout() {
      const authStore = useAuthStore();
      await authStore.logout();

      this.$router.push('/');
    }
  }
});
</script>
