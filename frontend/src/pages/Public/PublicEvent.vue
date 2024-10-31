<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">Events</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-space />
        <q-select
          v-model="selectedEvent"
          :options="eventOptions"
          outlined
          dense
          color="primary"
          class="q-mr-sm"
          :clearable="selectedEvent !== null"
          emit-value
          map-options
        />
        <q-select
          v-model="selectedStatus"
          :options="statusOptions"
          outlined
          dense
          color="primary"
          class="q-mr-sm"
          :clearable="selectedStatus !== null"
          emit-value
          map-options
        />
        <q-input
          rounded
          outlined
          dense
          color="primary"
          v-model="search"
          placeholder="Search by team or game name"
          class="q-mr-sm"
        >
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </div>
    </div>

    <div class="card-container">
      <q-card v-for="game in games" :key="game.id" class="my-card m-1" flat bordered>
        <q-card-section>
          <h4 class="text-primary text-bold">{{ game.name }}</h4>
          <p class="text-body2 text-gray-500">{{ game.event ? game.event.name : 'N/A' }}</p>
          <div class="team-info">
            <div class="team-column">
              <img src="~/assets/logo.png" alt="Team 1" class="team-image" />
              <p class="text-body2"><span class="text-primary">{{ game.team1 ? game.team1.name : 'N/A' }}</span></p>
            </div>
            <div class="vs-text text-primary">vs</div>
            <div class="team-column">
              <img src="~/assets/logo.png" alt="Team 2" class="team-image" />
              <p class="text-body2"><span class="text-primary">{{ game.team2 ? game.team2.name : 'N/A' }}</span></p>
            </div>
          </div>
          <div class="game-status">
            <template v-if="game.status === 'pending'">
              <q-badge color="secondary" :label="formatDate(game.date, 'D MMMM YYYY')" />
            </template>
            <template v-else-if="game.status === 'completed'">
              <q-badge color="primary" label="Final Score" class="text-white" />
              <div class="score-container">
                <span :class="{'text-primary text-bold': game.team1_score > game.team2_score, 'text-gray-500': game.team1_score <= game.team2_score}">{{ game.team1_score }}</span> |
                <span :class="{'text-primary text-bold': game.team2_score > game.team1_score, 'text-gray-500': game.team2_score <= game.team1_score}">{{ game.team2_score }}</span>
              </div>
            </template>
            <template v-else-if="game.status === 'canceled'">
              <q-badge color="negative" class="text-white" label="Canceled" />
            </template>
          </div>
        </q-card-section>
      </q-card>
    </div>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue';
import gameService from 'src/services/gameService';
import eventService from 'src/services/eventService';
import dateMixin from 'src/utils/mixins/dateMixin';
import handleMedia from 'src/utils/mixins/handleMedia';

export default defineComponent({
  mixins: [dateMixin, handleMedia],
  data() {
    return {
      games: [],
      gameData: [],
      search: '',
      selectedEvent: null,
      selectedStatus: null,
      events: [],
    };
  },
  computed: {
    statusOptions() {
      return [
        { label: 'All Statuses', value: null },
        { label: 'Completed', value: 'completed' },
        { label: 'Pending', value: 'pending' },
        { label: 'Canceled', value: 'canceled' },
      ];
    },
    eventOptions() {
      return [
        { label: 'Select Event', value: null, disabled: true },
        ...this.events.map(event => ({ label: event.name, value: event.id })),
      ];
    },
  },
  watch: {
    search() {
      this.debounceFetchGames();
    },
    selectedEvent() {
      this.fetchGames();
    },
    selectedStatus() {
      this.fetchGames();
    },
  },
  mounted() {
    this.fetchGames();
    this.fetchEvents();
  },
  methods: {
    async fetchGames() {
      try {
        const response = await gameService.getGames({
          search: this.search,
          event: this.selectedEvent,
          status: this.selectedStatus,
        });
        this.games = response.data.body || [];
      } catch (error) {
        console.error('Error fetching games:', error);
      }
    },
    async fetchEvents() {
      try {
        const response = await eventService.getEvents();
        this.events = response.data.body || [];
      } catch (error) {
        console.error('Error fetching events:', error);
      }
    },
    debounceFetchGames() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchGames();
      }, 1000);
    },
  },
});
</script>

<style scoped>
.card-container {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
}

.my-card {
  width: 500px;
  transition: transform 0.2s;
}

.my-card:hover {
  transform: scale(1.02);
}

.team-info {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 10px;
  height: 80px;
}

.team-column {
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 200px;
}

.team-image {
  width: 60px;
  height: auto;
  margin-bottom: 5px;
}

.vs-text {
  margin: 0 10px;
  font-size: 1.5rem;
  color: #000;
}

.game-status {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-top: 20px;
}

.score-container {
  margin-top: 5px;
}
</style>

