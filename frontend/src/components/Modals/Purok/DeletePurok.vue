<template>
  <q-dialog v-model="modalStore.showDeletePurokModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Purok</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the purok "<strong>{{ localForm.name }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this purok may also remove any associated data.</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Delete" color="negative" class="q-mr-sm" @click="confirmDelete" />
        <q-btn label="Cancel" color="grey" @click="closeModal" />
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import purokService from 'src/services/purokService';

export default {
  props: {
    fetchPuroks: {
      type: Function,
      required: true,
    },
    deleteData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localForm: {
        id: this.deleteData.id,
        name: this.deleteData.name,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue.id;
        this.localForm.name = newValue.name;
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeletePurokModal(false);
    },
    async confirmDelete() {
      try {
        const response = await purokService.deletePurok({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchPuroks();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting purok.',
        });
      }
    },
  },
};
</script>
