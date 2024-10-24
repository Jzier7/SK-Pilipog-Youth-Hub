import { defineStore } from 'pinia'
import announcementService from "src/services/announcementService";

export const useAnnouncementStore = defineStore('announcement', {
  state: () => ({
    announcementData: null,
  }),
  actions: {

    async fetchAnnouncement() {
      try {
        const response = await announcementService.getAllAnnouncement({
          latest: 'latest'
        });
        this.announcementData = response.data.body;
      } catch (error) {
        console.error("Error fetching announcement: ", error);
      }
    },

  },
})

