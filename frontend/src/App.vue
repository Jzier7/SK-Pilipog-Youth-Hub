<template>
  <router-view />
</template>

<script>
import { defineComponent } from 'vue';
import { useAuthStore } from 'src/stores/modules/authStore';
import { USER_ROLES } from 'src/utils/constants';

export default defineComponent({
  name: 'App',
  async created() {
    const authStore = useAuthStore();
    const response = await authStore.checkAuth();

    if (response) {
      if (response?.role.slug === USER_ROLES.SUPERADMIN) {
        this.$router.push('/superadmin/dashboard');
      } else if (response?.role.slug === USER_ROLES.ADMIN) {
        this.$router.push('/admin/dashboard');
      } else if (response?.role.slug === USER_ROLES.USER){
        this.$router.push('/user/home');
      } else {
        return;
      }
    }

  }
});
</script>
