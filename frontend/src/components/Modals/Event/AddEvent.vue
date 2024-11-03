<template>
  <q-dialog v-model="modalStore.showAddEventModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Event</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Event Name" />

        <q-select
          v-model="localForm.category"
          :options="categoryOptions"
          class="q-mb-md"
          outlined
          color="primary"
          :clearable="localForm.category !== null"
          emit-value
          map-options
          use-input
          input-debounce="0"
          label="Category"
          @filter="filterCategories"
          option-label="name"
          option-value="id"
        />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addEvent"></q-btn>
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
import eventService from 'src/services/eventService';
import categoryService from 'src/services/categoryService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
  },
  props: {
    fetchEvents: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        name: '',
        category: '',
      },
      originalCategoriesOptions: [],
      categoryOptions: [],
      errors: {},
      modalStore: useModalStore(),
    };
  },
  mounted() {
    this.fetchCategoryData();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddEventModal(false);
      this.resetForm();
    },
    resetForm() {
      this.localForm = {
        name: '',
        category: '',
      };
      this.errors = {};
    },
    async addEvent() {
      try {
        const response = await eventService.storeEvent({
          name: this.localForm.name,
          category: this.localForm.category,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchEvents();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding event.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchCategoryData() {
      try {
        const response = await categoryService.getAllCategories();
        this.categoryOptions = response.data.body || [];
        this.originalCategoriesOptions = [...this.categoryOptions];

        if (this.categoryOptions.length === 0) {
          Notify.create({
            type: 'warning',
            position: 'top',
            textColor: 'white',
            timeout: 10000,
            message: 'No categories found. Please add categories before creating events.'
          });
        }

      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    filterCategories(val, update) {
      if (val === '') {
        update(() => {
          this.categoryOptions = [...this.originalCategoriesOptions];
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.categoryOptions = this.originalCategoriesOptions.filter(category => category.name.toLowerCase().includes(needle));
      });
    },
  },
};
</script>

<style scoped>
.text-primary {
  color: #3f51b5;
}
</style>
