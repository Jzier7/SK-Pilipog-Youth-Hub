<template>
  <q-dialog v-model="modalStore.showDeleteTermModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Term</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the term "<strong>{{ localForm.label }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this term may also remove any associated officials.</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Delete" color="negative" class="q-mr-sm" @click="confirmDelete"></q-btn>
        <q-btn label="Cancel" color="grey" @click="closeModal"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import termService from 'src/services/termService';

export default {
  props: {
    fetchTerms: {
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
        label: this.deleteData.label,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue.id;
        this.localForm.label = newValue.label;
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteTermModal(false);
    },
    async confirmDelete() {
      try {
        const response = await termService.deleteTerm({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchTerms();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting term.'
        });
      }
    }
  }
};
</script>

