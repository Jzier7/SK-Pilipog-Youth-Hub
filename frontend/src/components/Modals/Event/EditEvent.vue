<template>
  <q-dialog v-model="modalStore.showEditEventModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Event</h3>
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
          <q-btn label="Save" color="primary" @click="editEvent"></q-btn>
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
    editData: {
      type: Object,
      required: true
    }
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
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.setInitialData(newValue);
      }
    }
  },
  mounted() {
    this.fetchCategoryData();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditEventModal(false);
      this.resetForm();
    },
    resetForm() {
      this.errors = {};
      this.setInitialData(this.editData);
    },
    setInitialData(editData) {
      if (editData) {
        this.localForm.name = editData.name || '';
        this.localForm.category = editData.category?.id || '';
      }
    },
    async editEvent() {
      try {
        const response = await eventService.updateEvent({
          id: this.editData.id,
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
          message: error.response?.data?.message || 'Error editing event.'
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

