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
                <div class="text-h6">Number of Voters</div>
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
                <div class="text-h6">Upcoming Event</div>
                <div class="text-subtitle1 text-grey-7">{{ upcomingEvent.title }}</div>
                <div class="text-body2 text-grey-6">Date: {{ upcomingEvent.date }}</div>
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
import { useUserStore } from 'src/stores/modules/userStore';

export default {
  components: {
    BarChart: defineAsyncComponent(() => import('components/BarChart/BarChart.vue')),
  },
  data() {
    return {
      userData: {},
      votersCount: 0,
      usersCountPerPurok: [],
      upcomingEvent: {
        title: 'Community Meeting',
        date: 'September 15, 2024'
      },
    };
  },
  async mounted() {
    const userStore = useUserStore();

    await userStore.fetchUser();
    this.userData = userStore.userData;

    await userStore.fetchVotersCount();
    this.votersCount = userStore.votersCount;

    await userStore.fetchUsersCountPerPurok();
    this.usersCountPerPurok = userStore.usersCountPerPurok;
  }
};
</script>

