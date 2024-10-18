import { defineStore } from 'pinia'

export const useModalStore = defineStore('modal', {
  state: () => ({
    showAddAnnouncementModal: false,
    showEditAnnouncementModal: false,
    showDeleteAnnouncementModal: false,
    showViewAnnouncementModal: false,

    showAddUserModal: false,
    showEditUserModal: false,
    showDeleteUserModal: false,
    showProofOfVoterModal: false,
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

    //User
    setShowAddUserModal(params) {
      this.showAddUserModal = params;
    },
    setShowEditUserModal(params) {
      this.showEditUserModal = params;
    },
    setShowDeleteUserModal(params) {
      this.showDeleteUserModal = params;
    },
    setShowProofOfVoterModal(params) {
      this.showProofOfVoterModal = params;
    },

  },
})

