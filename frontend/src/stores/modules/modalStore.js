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

    showAddOfficialModal: false,
    showEditOfficialModal: false,
    showDeleteOfficialModal: false,

    showAddTermModal: false,
    showEditTermModal: false,
    showDeleteTermModal: false,

    showAddPositionModal: false,
    showEditPositionModal: false,
    showDeletePositionModal: false,
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

    //Official
    setShowAddOfficialModal(params) {
      this.showAddOfficialModal = params;
    },
    setShowEditOfficialModal(params) {
      this.showEditOfficialModal = params;
    },
    setShowDeleteOfficialModal(params) {
      this.showDeleteOfficialModal = params;
    },

    //Term
    setShowAddTermModal(params) {
      this.showAddTermModal = params;
    },
    setShowEditTermModal(params) {
      this.showEditTermModal = params;
    },
    setShowDeleteTermModal(params) {
      this.showDeleteTermModal = params;
    },

    //Position
    setShowAddPositionModal(params) {
      this.showAddPositionModal = params;
    },
    setShowEditPositionModal(params) {
      this.showEditPositionModal = params;
    },
    setShowDeletePositionModal(params) {
      this.showDeletePositionModal = params;
    },

  },
})

