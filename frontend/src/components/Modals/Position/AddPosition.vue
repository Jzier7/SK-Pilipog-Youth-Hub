<template>
  <q-dialog v-model="modalStore.showAddPositionModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Position</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Position Name" />
        <CustomInput v-model="localForm.level" label="Level" type="number" />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addPosition"></q-btn>
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
import positionService from 'src/services/positionService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
  },
  props: {
    fetchPositions: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        name: '',
        level: '',
      },
      errors: {},
      modalStore: useModalStore(),
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddPositionModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        name: '',
        level: '',
      };
      this.errors = {};
    },
    async addPosition() {
      try {
        const response = await positionService.storePosition({
          name: this.localForm.name,
          level: this.localForm.level
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchPositions();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding position.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    }
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>
