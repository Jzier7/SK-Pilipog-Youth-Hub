<template>
  <q-dialog v-model="modalStore.showAddPurokModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Purok</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Purok Name" />
        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addPurok" />
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
  },
  data() {
    return {
      localForm: {
        name: '',
      },
      errors: {},
      modalStore: useModalStore(),
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddPurokModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        name: '',
      };
      this.errors = {};
    },
    async addPurok() {
      try {
        const response = await purokService.storePurok(this.localForm);
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
          message: error.response?.data?.message || 'Error adding purok.',
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
  },
};
</script>

<style lang="scss" scoped></style>
