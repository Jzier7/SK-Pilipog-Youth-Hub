<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">ANNOUNCEMENT</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="row justify-between">
      <q-btn
        dense
        label="Add Announcement"
        color="primary"
        @click="openAddModal"
        style="text-transform: capitalize;"
      ></q-btn>

      <form @submit.prevent="fetchAnnouncements">
        <q-input
          rounded
          outlined
          dense
          v-model="search"
          placeholder="Search Announcements"
          @input="filterAnnouncements"
          color="primary"
        >
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </form>
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
          <p class="text-body2 text-gray-500">{{ announcement.category.name }}</p>
          <p class="text-caption text-secondary">{{ timeAgo(announcement.updated_at) }}</p>
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
          <q-btn
            label="Edit"
            color="primary"
            @click="openEditModal(announcement)"
            style="text-transform: capitalize;"
            class="q-mr-md"
          />
          <q-btn
            label="Delete"
            color="negative"
            style="text-transform: capitalize;"
            @click="openDeleteModal(announcement)"
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

    <AddAnnouncementModal :fetchAnnouncements="fetchAnnouncements"/>
    <EditAnnouncementModal :fetchAnnouncements="fetchAnnouncements" :editData="announcementData"/>
    <DeleteAnnouncementModal :fetchAnnouncements="fetchAnnouncements" :deleteData="announcementData"/>
    <ViewAnnouncementModal :viewData="announcementData"/>
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import announcementService from 'src/services/announcementService';
import dateMixin from 'src/utils/mixins/dateMixin'
import handleMedia from 'src/utils/mixins/handleMedia';

export default {
  components: {
    AddAnnouncementModal: defineAsyncComponent(() => import('components/Modals/Announcement/AddAnnouncement.vue')),
    EditAnnouncementModal: defineAsyncComponent(() => import('components/Modals/Announcement/EditAnnouncement.vue')),
    DeleteAnnouncementModal: defineAsyncComponent(() => import('components/Modals/Announcement/DeleteAnnouncement.vue')),
    ViewAnnouncementModal: defineAsyncComponent(() => import('components/Modals/Announcement/ViewAnnouncement.vue')),
  },
  mixins: [dateMixin, handleMedia],
  data() {
    return {
      announcements: [],
      announcementData: [],
      search: '',
      currentPage: 1,
      pageSize: 5,
      lastPage: 1,
      total: 0,
      orderBy: 'desc',
      debounceTimeout: null,
    };
  },
  watch: {
    search() {
      this.currentPage = 1;

      clearTimeout(this.debounceTimeout);

      this.debounceTimeout = setTimeout(() => {
        this.fetchAnnouncements();
      }, 1000);
    },
  },
  mounted() {
    this.fetchAnnouncements();
  },
  methods: {
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddAnnouncementModal(true);
    },
    openEditModal(editData) {
      this.announcementData = editData;

      const modalStore = useModalStore();
      modalStore.setShowEditAnnouncementModal(true);
    },
    openDeleteModal(deleteData) {
      this.announcementData = deleteData;

      const modalStore = useModalStore();
      modalStore.setShowDeleteAnnouncementModal(true);
    },
    openViewModal(viewData) {
      this.announcementData = viewData;

      const modalStore = useModalStore();
      modalStore.setShowViewAnnouncementModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchAnnouncements();
    },
    filterAnnouncements() {
      this.fetchAnnouncements();
    },
    async fetchAnnouncements() {
      try {
        const response = await announcementService.getPaginatedAnnouncement({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          orderBy: this.orderBy
        });
        this.announcements = response.data.body || [];

        const details = response.data.details;
        if (details) {
          this.currentPage = details.current_page || 1;
          this.lastPage = details.last_page || 1;
          this.total = details.total || 0;
        }
      } catch (error) {
        console.error('Error fetching announcements:', error);
      }
    },
  },
};
</script>

<style scoped>
.q-card {
  margin-top: 16px;
}
</style>
