<template>
  <q-dialog v-model="modalStore.showDeleteAnnouncementModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Announcement</h3>
      <div class="text-primary">
        <p>Are you sure you want to delete the announcement titled "<strong>{{ localForm.title }}</strong>"?</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Cancel" color="grey" @click="closeModal"></q-btn>
        <q-btn label="Delete" color="negative" class="q-ml-sm" @click="confirmDelete"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar'
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import announcementService from 'src/services/announcementService';

export default {
  props: {
    fetchAnnouncements: {
      type: Function,
      required: true
    },
    deleteData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localForm: {
        id: this.deleteData.id,
        title: this.deleteData.title,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue.id,
        this.localForm.title = newValue.title;
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteAnnouncementModal(false);
    },
    async confirmDelete() {
      try {
        const response = await announcementService.deleteAnnouncement({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchAnnouncements();
        this.closeModal();

      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response.data.message
        });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
</style>

