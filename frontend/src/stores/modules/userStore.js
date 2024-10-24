import { defineStore } from 'pinia';
import userService from 'src/services/userService';

export const useUserStore = defineStore('user', {
  state: () => ({
    userData: {},
    votersCount: 0,
    usersCountPerPurok: [],
  }),
  actions: {
    async fetchUser() {
      try {
        const response = await userService.getUser();
        this.userData = response.data.body;
      } catch (error) {
        console.error("Error fetching user: ", error);
      }
    },
    async updateUser(data) {
      try {
        const response = await userService.updateUser(data);
        this.userData = response.data.body;
        return { success: true, response };
      } catch (error) {
        console.error("Error editing user: ", error);
        return { success: false, error: error.response.data };
      }
    },
    setUserData(data) {
      this.userData = data;
    },
    async fetchVotersCount() {
      try {
        const response = await userService.getVotersCount();
        this.votersCount = response.data.body;
      } catch (error) {
        console.error("Error fetching voters count: ", error);
      }
    },
    async fetchUsersCountPerPurok() {
      try {
        const response = await userService.getUsersCountPerPurok();
        this.usersCountPerPurok = response.data.body;
      } catch (error) {
        console.error("Error fetching users count per purok: ", error);
      }
    },
    removeUser() {
      localStorage.removeItem('user');
    }
  },
  persist: {
    pick: ['userData'],
  },
});

