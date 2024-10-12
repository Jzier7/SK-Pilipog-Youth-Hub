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
        category: this.editData.category ? this.editData.category.id : ''
      },
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
          category: newValue.category ? newValue.category.id : ''
        };
      }
    }
  },
  computed: {
    selectedCategoryLabel() {
      const category = this.categories.find(cat => cat.id === this.localForm.category);
      return category ? category.name : '';
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
      try {
        const response = await announcementService.updateAnnouncement({
          id: this.localForm.id,
          title: this.localForm.title,
          category: this.localForm.category,
          content: this.localForm.content
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
  },
};
</script>

<style lang="scss" scoped>
.editor {
  color: $primary;
}
</style>

