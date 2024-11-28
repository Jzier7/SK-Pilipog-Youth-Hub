<template>
  <q-dialog v-model="modalStore.showEditPurokModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Purok</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Purok Name" />
        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="save" />
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm" />
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import purokService from 'src/services/purokService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
  },
  props: {
    fetchPuroks: {
      type: Function,
      required: true,
    },
    editData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localForm: {
        ...this.editData,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.localForm = { ...newValue };
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditPurokModal(false);
    },
    async save() {
      try {
        const response = await purokService.updatePurok({
          id: this.localForm.id,
          name: this.localForm.name,
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
          message: error.response?.data?.message || 'Error saving purok data.',
        });
      }
    },
  },
};
</script>
