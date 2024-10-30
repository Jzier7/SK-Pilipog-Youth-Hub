<template>
  <q-dialog v-model="modalStore.showEditEventModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Event</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Event Name" />

        <CustomSelect
          v-model="localForm.category"
          :options="categoryData.map(category => ({ label: category.name, value: category.id }))"
          label="Category"
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
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
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
      categoryData: [],
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
      this.clearForm();
    },
    clearForm() {
      this.errors = {};
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
        this.categoryData = response.data.body || [];
      } catch (error) {
        console.error('Error fetching categories:', error);
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

