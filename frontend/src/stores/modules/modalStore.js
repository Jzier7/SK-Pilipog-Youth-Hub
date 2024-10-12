import { defineStore } from 'pinia'

export const useModalStore = defineStore('modal', {
  state: () => ({
    showAddAnnouncementModal: false,
    showEditAnnouncementModal: false,
    showDeleteAnnouncementModal: false,
    showViewAnnouncementModal: false,
  }),
  actions: {

    //Announcement
    setShowAddAnnouncementModal(params) {
      this.showAddAnnouncementModal = params;
    },
    setShowEditAnnouncementModal(params) {
      this.showEditAnnouncementModal = params;
    },
    setShowDeleteAnnouncementModal(params) {
      this.showDeleteAnnouncementModal = params;
    },
    setShowViewAnnouncementModal(params) {
      this.showViewAnnouncementModal = params;
    },

  },
})

