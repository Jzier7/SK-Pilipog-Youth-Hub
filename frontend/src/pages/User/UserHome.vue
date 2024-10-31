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
        class="q-mr-sm"
        :clearable="true"
        emit-value
        map-options
      />
    </div>

    <div class="q-mt-md">
      <div v-if="filteredAnnouncements.length === 0" class="row justify-center q-ma-lg">
        <p>No announcements found.</p>
      </div>
      <q-card
        v-for="announcement in filteredAnnouncements"
        :key="announcement.id"
        flat
        bordered
        class="q-pa-md q-mb-md border border-gray-300 rounded-lg shadow-lg"
      >
        <q-card-section class="overflow-hidden">
          <h2 class="text-h6 font-bold text-primary">{{ announcement.title }}</h2>
          <p class="text-body2 text-gray-500">{{ announcement.category.name }}</p>
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

    <ViewAnnouncementModal :viewData="announcementData" />
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import { useUserStore } from 'src/stores/modules/userStore';
import { useAnnouncementStore } from 'src/stores/modules/announcementStore';
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
      categories: [],
      selectedCategory: null,
    };
  },
  computed: {
    categoryOptions() {
      return [
        { label: 'Select Category', value: null, disabled: true },
        ...this.categories.map(category => ({ label: category.name, value: category.id })),
      ];
    },
    filteredAnnouncements() {
      if (this.selectedCategory) {
        return this.announcements.filter(
          announcement => announcement.category.id === this.selectedCategory
        );
      }
      return this.announcements;
    },
  },
  async mounted() {
    const userStore = useUserStore();
    const announcementStore = useAnnouncementStore();

    await Promise.all([
      announcementStore.fetchAnnouncement(),
      userStore.fetchUser(),
      this.fetchCategories(),
    ]);

    this.userData = userStore.userData;
    this.announcements = announcementStore.announcementData;
  },
  methods: {
    openViewModal(viewData) {
      this.announcementData = viewData;

      const modalStore = useModalStore();
      modalStore.setShowViewAnnouncementModal(true);
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

<style scoped>
.q-gutter-sm {
  margin-bottom: 0.5rem;
}
</style>
