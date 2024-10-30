<template>
  <q-dialog v-model="modalStore.showEditAnnouncementModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Announcement</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.title" label="Title" />
        <CustomSelect
          v-model="localForm.category"
          :options="categories.map(category => ({ label: category.name, value: category.id }))"
          label="Categories"
        />
        <q-editor v-model="localForm.content" min-height="10rem" class="editor q-mb-md" />

        <CustomUploader
          label="Images"
          v-model="localForm.files"
          uploaderClass="q-mb-md"
          @file-added="replaceFiles"
        />

        <div v-if="oldFiles.length > 0" class="q-my-md text-center">
          <div v-for="(file, index) in oldFiles" :key="index" class="my-card q-mb-md">
            <img
              :src="getMediaURL(file)"
              alt="Image"
              style="max-width: 100%; max-height: 300px;"
            />
          </div>
        </div>

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
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
    CustomUploader: defineAsyncComponent(() => import('components/Widgets/CustomUploader.vue')),
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
        files: [] // New files that will be uploaded
      },
      oldFiles: this.editData.files || [], // Existing files
      modalStore: useModalStore(),
      categories: [],
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
        this.oldFiles = newValue.files || [];
      }
    }
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditAnnouncementModal(false);
    },
    async publish() {
      const formData = new FormData();
      formData.append('id', this.localForm.id);
      formData.append('title', this.localForm.title);
      formData.append('category', this.localForm.category);
      formData.append('content', this.localForm.content);

      // Add new files if any
      if (this.localForm.files.length > 0) {
        this.localForm.files.forEach(file => {
          formData.append('files[]', file);
        });
      } else {
        // If no new files, retain old files for upload
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
        this.categories = response.data.body || [];

      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    replaceFiles(newFiles) {
      if (newFiles.length > 0) {
        this.oldFiles = [];
        this.localForm.files = newFiles;
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

