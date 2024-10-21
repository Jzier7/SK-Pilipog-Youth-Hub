import { defineStore } from 'pinia'
import authService from "src/services/authService";
import { useUserStore } from './userStore';

export const useAuthStore = defineStore('auth', {
  actions: {
    async login(credentials) {
      const userStore = useUserStore();

      try {
        const response = await authService.login(credentials);
        userStore.setUserData(response.body);

        return {
          data: response.body,
          message: response.message,
          status: response.status
        };

      } catch (error) {
        console.error("Login failed:", error);

        return {
          data: null,
          message: 'Login failed, please check your credentials',
          status: error.status
        };

      }
    },

    async logout() {
      const userStore = useUserStore();

      try {
        const response = await authService.logout();
        userStore.clearUserData();

        return {
          data: response.body,
          message: response.message
        };

      } catch (error) {
        console.error("Logout failed:", error);

        return {
          data: null,
          message: 'Logout failed'
        };
      }
    },
  }
});

