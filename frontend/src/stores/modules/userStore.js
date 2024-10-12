import { defineStore } from 'pinia'
import userService from "src/services/userService";

export const useUserStore = defineStore('user', {
  state: () => ({
    userData: null,
    votersCount: 0,
    usersCountPerPurok: [],
  }),
  actions: {

    //Current User Data operations
    async fetchUser() {
      try {
        const response = await userService.getUser();
        this.userData = response.data.body;
      } catch (error) {
        console.error("Error fetching user: ", error);
      }
    },
    setUserData(data) {
      this.userData = data;
    },
    clearUserData() {
      this.userData = null;
    },

    //Users Data operations
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
    }
  },
})

