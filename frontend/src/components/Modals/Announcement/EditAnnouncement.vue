<template>
  <q-dialog v-model="modalStore.showEditAnnouncementModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-primary" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Announcement</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.title" label="Title" />

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
        <q-editor v-model="localForm.content" min-height="10rem" class="editor q-mb-md" />

        <CustomCroppa
          :existingImage="oldFiles"
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
import { Notify } from 'quasar'
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import categoryService from 'src/services/categoryService';
import announcementService from 'src/services/announcementService';
import handleMedia from 'src/utils/mixins/handleMedia';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomCroppa: defineAsyncComponent(() => import('components/Widgets/CustomCroppa.vue')),
  },
  mixins: [handleMedia],
  props: {
    fetchAnnouncements: {
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
        ...this.editData,
        category: this.editData.category ? this.editData.category.id : '',
        files: this.editData.files || [],
      },
      oldFiles: this.editData.files && this.editData.files.length > 0 ? this.getMediaURL(this.editData.files[0]) : [],
      modalStore: useModalStore(),
      originalCategoriesOptions: [],
      categoryOptions: [],
    };
  },
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.localForm = {
          ...newValue,
          category: newValue.category ? newValue.category.id : '',
        };
        this.oldFiles = newValue.files && newValue.files.length > 0 ? this.getMediaURL(newValue.files[0]) : [];
      }
    }
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditAnnouncementModal(false);
      this.resetForm();
    },
    resetForm() {
      this.localForm = {
        ...this.editData,
        category: this.editData.category ? this.editData.category.id : '',
        files: this.editData.files ? this.editData.files : null,
      };
      this.oldFiles = this.getMediaURL(this.editData.files[0]) || [];
    },
    updateCroppedImage(croppedImage) {
      this.localForm.files = croppedImage;
    },
    async publish() {
      const formData = new FormData();
      formData.append('id', this.localForm.id);
      formData.append('title', this.localForm.title);
      formData.append('category', this.localForm.category);
      formData.append('content', this.localForm.content);

      if (this.localForm.files) {
        formData.append('file', this.localForm.files);
      } else {
        this.oldFiles.forEach(file => {
          formData.append('oldFiles[]', file);
        });
      }

      try {
        const response = await announcementService.updateAnnouncement(formData, {
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
    replaceFiles(newFile) {
      if (newFile) {
        this.oldFiles = [];
        this.localForm.files = newFile;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.editor {
  color: $primary;
}
.my-card {
  position: relative;
}
.delete-btn {
  position: absolute;
  top: 5px;
  right: 5px;
}
</style>


