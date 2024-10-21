<template>
  <q-dialog v-model="modalStore.showEditTermModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Term</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.startDate" label="Start Date" type="date" />
        <CustomInput v-model="localForm.endDate" label="End Date" type="date" />

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="editTerm"></q-btn>
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm"></q-btn>
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import termService from 'src/services/termService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
  },
  props: {
    fetchTerms: {
      type: Function,
      required: true
    },
    editData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localForm: {
        startDate: '',
        endDate: '',
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.setInitialData(newValue);
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditTermModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        startDate: '',
        endDate: '',
      };
    },
    setInitialData(editData) {
      if (editData) {
        this.localForm.startDate = editData.start_date || '';
        this.localForm.endDate = editData.end_date || '';
      }
    },
    async editTerm() {
      try {
        const response = await termService.updateTerm({
          id: this.editData.id,
          start_date: this.localForm.startDate,
          end_date: this.localForm.endDate,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchTerms();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error editing term.',
        });
      }
    },
  },
};
</script>
