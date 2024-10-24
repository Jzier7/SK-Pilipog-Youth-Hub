<template>
  <q-page padding>
    <q-card class="q-my-lg bg-primary" flat bordered>
      <q-card-section>
        <q-toolbar class="q-pa-none">
          <q-toolbar-title>
            <h2 class="text-white">Hi! {{ userData.username }}</h2>
          </q-toolbar-title>
        </q-toolbar>
      </q-card-section>
    </q-card>

    <div class="row gap-4 items-stretch">
      <!-- Number of Voters Card -->
      <div class="col">
        <q-card flat bordered class="full-height">
          <q-card-section>
            <div class="row place-content-center">
              <q-icon name="people" size="5rem" class="q-mr-lg" color="primary" />
              <div class="place-content-center">
                <div class="text-h6 text-primary"><strong>Number of Voters</strong></div>
                <div class="text-subtitle1 text-grey-7">{{ votersCount }} Registered Voters</div>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>

      <!-- Upcoming Event Card -->
      <div class="col">
        <q-card flat bordered class="full-height">
          <q-card-section>
            <div class="row place-content-center">
              <q-icon name="event" size="5rem" class="q-mr-lg" color="primary" />
              <div class="place-content-center">
                <div class="text-h6 text-primary">
                  <strong>Announcement</strong>
                  <span v-show="announcementsToday.length > 0" class="text-gray-500"> ( {{ announcementsToday.length }} today )</span>
                </div>
                <div class="text-subtitle1 text-gray-500 title-truncate">
                  {{ currentAnnouncement.title ? currentAnnouncement.title : 'No announcement today' }}
                </div>
                <div v-if="currentAnnouncement.created_at" class="text-caption text-grey-6">
                  {{ timeAgo(currentAnnouncement.created_at) }}
                </div>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <div class="q-mt-lg">
      <q-card flat bordered class="full-height">
        <q-card-section>
          <BarChart v-if="usersCountPerPurok.length" :data="usersCountPerPurok" />
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useAnnouncementStore } from 'src/stores/modules/announcementStore';
import { useUserStore } from 'src/stores/modules/userStore';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  components: {
    BarChart: defineAsyncComponent(() => import('components/BarChart/BarChart.vue')),
  },
  mixins: [dateMixin],
  data() {
    return {
      userData: {},
      votersCount: 0,
      usersCountPerPurok: [],
      latestAnnouncements: [],
      announcementsToday: [],
      currentAnnouncementIndex: 0,
      currentAnnouncement: {},
    };
  },
  async mounted() {
    const userStore = useUserStore();
    const announcementStore = useAnnouncementStore();

    await Promise.all([
      announcementStore.fetchAnnouncement(),
      userStore.fetchUser(),
      userStore.fetchVotersCount(),
      userStore.fetchUsersCountPerPurok(),
    ]);

    this.latestAnnouncements = announcementStore.announcementData;
    this.filterAnnouncementsToday();
    this.userData = userStore.userData;
    this.votersCount = userStore.votersCount;
    this.usersCountPerPurok = userStore.usersCountPerPurok;

    if (this.latestAnnouncements.length > 0) {
      this.currentAnnouncement = this.latestAnnouncements[this.currentAnnouncementIndex];
      this.startAnnouncementRotation();
    }
  },
  methods: {
    startAnnouncementRotation() {
      setInterval(() => {
        this.currentAnnouncementIndex =
          (this.currentAnnouncementIndex + 1) % this.latestAnnouncements.length;
        this.currentAnnouncement = this.latestAnnouncements[this.currentAnnouncementIndex];
      }, 10000);
    },
    filterAnnouncementsToday() {
      const today = new Date().toISOString().split('T')[0];
      this.announcementsToday = this.latestAnnouncements.filter((announcement) =>
        announcement.created_at.startsWith(today)
      );
    },
  },
};
</script>

<style scoped>

@media (max-width: 1670px) {
  .title-truncate {
    display: block;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 250px;
  }
}

@media (max-width: 900px) {
  .title-truncate {
    display: block;
    white-space: normal;
    overflow: visible;
    text-overflow: initial;
    max-width: none;
  }
}
</style>

