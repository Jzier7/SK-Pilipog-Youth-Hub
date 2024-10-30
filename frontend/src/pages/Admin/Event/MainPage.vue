<template>
  <q-page padding>
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h3 class="text-primary">{{ currentTitle }}</h3>
      </q-toolbar-title>
    </q-toolbar>

    <div>
      <q-tabs v-model="activeTab" dense class="text-primary q-pb-sm" active-color="primary">
        <q-tab name="events" label="Events" />
        <q-tab name="games" label="Games" />
        <q-tab name="teams" label="Teams" />
        <q-tab name="categories" label="Categories" />
      </q-tabs>

      <q-tab-panels v-model="activeTab" class="border">
        <q-tab-panel name="events">
          <EventsComponent />
        </q-tab-panel>
        <q-tab-panel name="games">
          <GamesComponent />
        </q-tab-panel>
        <q-tab-panel name="teams">
          <TeamsComponent />
        </q-tab-panel>
        <q-tab-panel name="categories">
          <CategoriesComponent />
        </q-tab-panel>
      </q-tab-panels>
    </div>
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  components: {
    EventsComponent: defineAsyncComponent(() => import('./Components/AdminEvent.vue')),
    CategoriesComponent: defineAsyncComponent(() => import('./Components/EventCategory.vue')),
    GamesComponent: defineAsyncComponent(() => import('./Components/EventGame.vue')),
    TeamsComponent: defineAsyncComponent(() => import('./Components/EventTeam.vue')),
  },
  data() {
    return {
      activeTab: 'events',
    };
  },
  computed: {
    currentTitle() {
      switch (this.activeTab) {
        case 'events':
          return 'EVENTS';
        case 'categories':
          return 'CATEGORIES';
        case 'games':
          return 'GAMES';
        case 'teams':
          return 'TEAMS';
        default:
          return '';
      }
    },
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>

