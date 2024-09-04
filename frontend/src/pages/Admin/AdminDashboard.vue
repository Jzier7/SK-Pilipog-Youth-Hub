<template>
  <q-page padding>
    <q-card class="q-my-lg bg-primary" flat bordered>
      <q-card-section>
        <q-toolbar class="q-pa-none">
          <q-toolbar-title>
            <h2 class="text-white">Hi! John Wilche</h2>
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
                <div class="text-subtitle1 text-grey-7">{{ numberOfVoters }} Registered Voters</div>
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

      <!-- Recent Announcement Card -->
      <div class="col">
        <q-card flat bordered class="full-height">
          <q-card-section>
            <div class="row place-content-center">
              <q-icon name="announcement" size="5rem" class="q-mr-lg" color="primary" />
              <div class="place-content-center">
                <div class="text-h6">Recent Announcement</div>
                <div class="text-subtitle1 text-grey-7">{{ recentAnnouncement.title }}</div>
                <div class="text-body2 text-grey-6">{{ recentAnnouncement.details }}</div>
              </div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <div class="q-mt-lg">
      <q-card flat bordered class="full-height">
        <q-card-section>
          <div>
            <BarChart />
          </div>
        </q-card-section>
      </q-card>
    </div>

  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  components: {
    BarChart: defineAsyncComponent(() => import('components/BarChart/BarChart.vue')),
  },
  data() {
    return {
      numberOfVoters: 1234,
      upcomingEvent: {
        title: 'Community Meeting',
        date: 'September 15, 2024'
      },
      recentAnnouncement: {
        title: 'New Voting Guidelines Released',
        details: 'Check the latest updates on our website.'
      }
    };
  }
};
</script>
