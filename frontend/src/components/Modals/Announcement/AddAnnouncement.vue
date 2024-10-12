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
        content: ''
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
        content: ''
      };
    },
    async publish() {
      try {
        const response = await announcementService.storeAnnouncement({
          title: this.form.title,
          category: this.form.category,
          content: this.form.content
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
