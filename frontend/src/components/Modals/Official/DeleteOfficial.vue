<template>
  <q-dialog v-model="modalStore.showDeleteOfficialModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Official</h3>
      <div class="text-primary">
        <p>Are you sure you want to delete the official "<strong>{{ localForm.name }}</strong>"?</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Cancel" color="grey" @click="closeModal"></q-btn>
        <q-btn label="Delete" color="negative" class="q-ml-sm" @click="confirmDelete"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import officialService from 'src/services/officialService';

export default {
  props: {
    fetchOfficials: {
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
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteOfficialModal(false);
    },
    async confirmDelete() {
      try {
        const response = await officialService.deleteOfficial({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchOfficials();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting official.'
        });
      }
    }
  }
};
</script>

