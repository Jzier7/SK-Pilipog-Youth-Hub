<template>
  <q-dialog v-model="modalStore.showAddCategoryModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Category</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Category Name" />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addCategory"></q-btn>
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
import categoryService from 'src/services/categoryService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
  },
  props: {
    fetchCategories: {
      type: Function,
      required: true
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
      this.modalStore.setShowAddCategoryModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm.name = '';
      this.errors = {};
    },
    async addCategory() {
      try {
        const response = await categoryService.storeCategory({
          name: this.localForm.name
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchCategories();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding category.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
  },
};
</script>

<style scoped>
.text-primary {
  color: #3f51b5;
}
</style>

