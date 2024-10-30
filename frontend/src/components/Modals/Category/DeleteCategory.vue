<template>
  <q-dialog v-model="modalStore.showDeleteCategoryModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Category</h3>
      <div class="text-primary">
        <p>Are you sure you want to delete the category "<strong>{{ localForm.name }}</strong>"?</p>
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
import categoryService from 'src/services/categoryService';

export default {
  props: {
    fetchCategories: {
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
      this.modalStore.setShowDeleteCategoryModal(false);
    },
    async confirmDelete() {
      try {
        const response = await categoryService.deleteCategory({
          id: this.localForm.id,
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
          message: error.response?.data?.message || 'Error deleting category.'
        });
      }
    }
  }
};
</script>

<style scoped>
.text-negative {
  color: #f44336;
}
.text-primary {
  color: #3f51b5;
}
</style>
