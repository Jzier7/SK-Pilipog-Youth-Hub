<template>
  <q-dialog v-model="modalStore.showAddAnnouncementModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Announcement</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="form.title" label="Title" />
        <CustomSelect
          v-model="form.category"
          :options="categories.map(category => ({ label: category.name, value: category.id }))"
          label="Categories"
        />
        <q-editor v-model="form.content" min-height="10rem" class="editor q-mb-md" />

        <CustomUploader
          label="Image"
          v-model="form.files"
          uploaderClass="q-mb-md"
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

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
    CustomUploader: defineAsyncComponent(() => import('components/Widgets/CustomUploader.vue')),
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
        files: []
      },
      modalStore: useModalStore(),
      categories: [],
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
        files: []
      };
    },
    async publish() {
      const formData = new FormData();
      formData.append('title', this.form.title);
      formData.append('category', this.form.category);
      formData.append('content', this.form.content);

      this.form.files.forEach(file => {
        formData.append('files[]', file);
      });

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
        this.categories = response.data.body || [];

      if (this.categories && this.categories.length === 0) {
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
  },
};
</script>

<style lang="scss" scoped>
.editor {
  color: $primary;
}
</style>
