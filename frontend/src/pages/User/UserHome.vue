<template>
  <q-page padding>
    <q-card class="q-my-lg bg-primary" flat bordered>
      <q-card-section>
        <q-toolbar>
          <q-toolbar-title>
            <h2 class="text-white">Hi! {{ userData.username }}</h2>
          </q-toolbar-title>
        </q-toolbar>
      </q-card-section>
    </q-card>

    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-space />
      <q-select
        v-model="selectedCategory"
        :options="categoryOptions"
        outlined
        dense
        color="primary"
        :clearable="selectedCategory !== null"
        emit-value
        map-options
        use-input
        input-debounce="0"
        label="Select Category"
        @filter="filterCategories"
        option-label="name"
        option-value="id"
      />
    </div>

    <div class="q-mt-md">
      <div v-if="announcements.length === 0" class="row justify-center q-ma-lg">
        <p>No announcements found.</p>
      </div>
      <q-card
        v-for="announcement in announcements"
        :key="announcement.id"
        flat
        bordered
        class="q-pa-md q-mb-md border border-gray-300 rounded-lg shadow-lg"
      >
        <q-card-section class="overflow-hidden">
          <h2 class="text-h6 font-bold text-primary">{{ announcement.title }}</h2>
          <p class="text-body2 text-gray-500">{{ announcement.category?.name || 'No category' }}</p>
          <p class="text-caption text-secondary">{{ timeAgo(announcement.created_at) }}</p>
          <q-separator class="my-2" />
          <div class="overflow-hidden max-h-10">
            <p class="text-body2 text-ellipsis" v-html="announcement.content"></p>
          </div>
        </q-card-section>

        <q-card-actions class="row justify-end">
          <q-btn
            label="View"
            color="secondary"
            @click="openViewModal(announcement)"
            style="text-transform: capitalize;"
            class="q-mr-md"
          />
        </q-card-actions>
      </q-card>
    </div>

    <div class="row justify-center q-mt-md">
      <q-pagination
        v-model="currentPage"
        :max="lastPage"
        @update:model-value="updatePage"
        direction-links
      />
    </div>

    <ViewAnnouncementModal :viewData="announcementData" />
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import { useUserStore } from 'src/stores/modules/userStore';
import announcementService from 'src/services/announcementService';
import categoryService from 'src/services/categoryService';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  components: {
    ViewAnnouncementModal: defineAsyncComponent(() => import('components/Modals/Announcement/ViewAnnouncement.vue')),
  },
  mixins: [dateMixin],
  data() {
    return {
      userData: {},
      announcements: [],
      announcementData: [],
      currentPage: 1,
      pageSize: 5,
      lastPage: 1,
      total: 0,
      selectedCategory: null,
      originalCategoriesOptions: [],
      categoryOptions: [],
    };
  },
  watch: {
    selectedCategory() {
      this.fetchAnnouncement();
    },
  },
  async mounted() {
    const userStore = useUserStore();

    await Promise.all([
      userStore.fetchUser(),
      this.fetchAnnouncement(),
      this.fetchCategories(),
    ]);

    this.userData = userStore.userData;
  },
  methods: {
    openViewModal(viewData) {
      this.announcementData = viewData;

      const modalStore = useModalStore();
      modalStore.setShowViewAnnouncementModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchAnnouncement();
    },
    async fetchAnnouncement() {
      try {
        const response = await announcementService.getPaginatedAnnouncement({
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          category: this.selectedCategory,
        });

        this.announcements = response.data.body || [];

        const details = response.data.details;
        if (details) {
          this.currentPage = details.current_page || 1;
          this.lastPage = details.last_page || 1;
          this.total = details.total || 0;
        }
      } catch (error) {
        console.error('Error fetching announcement:', error);
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
  },
};
</script>

<style scoped>
.q-gutter-sm {
  margin-bottom: 0.5rem;
}
</style>

