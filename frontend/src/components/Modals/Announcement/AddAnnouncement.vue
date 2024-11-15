<template>
  <q-dialog v-model="modalStore.showAddAnnouncementModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-primary" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Announcement</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="form.title" label="Title" />

        <q-select
          v-model="form.category"
          :options="categoryOptions"
          class="q-mb-md"
          outlined
          color="primary"
          :clearable="form.category !== null"
          emit-value
          map-options
          use-input
          input-debounce="0"
          label="Category"
          @filter="filterCategories"
          option-label="name"
          option-value="id"
        />

        <q-editor v-model="form.content" min-height="10rem" class="editor q-mb-md" />

        <CustomCroppa
          @imageCropped="updateCroppedImage"
          :stencil-size="{
              width: 700,
              height: 500
            }"
          :stencil-props="{
              handlers: {},
              movable: false,
              resizable: false,
            }"
        />

        <div class="row justify-end">
          <q-btn label="Publish" color="primary" @click="publish"></q-btn>
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
import announcementService from 'src/services/announcementService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomCroppa: defineAsyncComponent(() => import('components/Widgets/CustomCroppa.vue')),
  },
  props: {
    fetchAnnouncements: {
      type: Function,
      required: true,
    },
  },
  data() {
    return {
      form: {
        title: '',
        category: '',
        content: '',
        croppedImage: null,
      },
      modalStore: useModalStore(),
      originalCategoriesOptions: [],
      categoryOptions: [],
    };
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddAnnouncementModal(false);
    },
    resetForm() {
      this.form = {
        title: '',
        category: '',
        content: '',
        croppedImage: null,
      };
    },
    updateCroppedImage(croppedImage) {
      this.form.croppedImage = croppedImage;
    },
    async publish() {
      const formData = new FormData();
      formData.append('title', this.form.title);
      formData.append('category', this.form.category);
      formData.append('content', this.form.content);

      if (this.form.croppedImage) {
        formData.append('file', this.form.croppedImage);
      }

      try {
        const response = await announcementService.storeAnnouncement(formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchAnnouncements();
        this.closeModal();
        this.resetForm();

      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response.data.message
        });
      }
    },
    async fetchCategories() {
      try {
        const response = await categoryService.getAllCategories();
        this.categoryOptions = response.data.body || [];
        this.originalCategoriesOptions = [...this.categoryOptions];

        if (this.categoryOptions && this.categoryOptions.length === 0) {
          Notify.create({
            type: 'warning',
            position: 'top',
            textColor: 'white',
            timeout: 10000,
            message: 'No categories found. Please add categories on the Events page before creating announcements.'
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

<style lang="scss" scoped>
.editor {
  color: $primary;
}
</style>
