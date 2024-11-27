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
        <q-select v-model="selectedEvent" :options="eventOptions" outlined dense color="primary"
          :clearable="selectedEvent !== null" emit-value map-options use-input input-debounce="0" label="Select Event"
          @filter="filterEvents" option-label="name" option-value="id" />
        <q-select v-model="selectedStatus" :options="statusOptions" outlined dense color="primary" class="q-mr-sm"
          :clearable="selectedStatus !== null" emit-value map-options />
        <q-input rounded outlined dense color="primary" v-model="search" placeholder="Search by team or game name"
          class="q-mr-sm">
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
          <p class="text-body2 text-gray-500">{{ game.event ? `${game.event.name} (${game.event.category.name})` : 'N/A'
            }}
          </p>
          <div class="team-info">
            <div class="team-column">
              <div v-if="game.team1" class="team-clickable" @click="openViewModal(game.team1)">
                <img src="~/assets/logo.png" alt="Team 1" class="team-image" />
                <p class="text-body2">
                  <span class="text-primary">{{ game.team1.name }}</span>
                </p>
              </div>
              <p v-else class="text-body2">
                <span class="text-primary">N/A</span>
              </p>
              <div class="like-section">
                <q-btn dense flat :icon="isLikedByUser(game.team1.liked_by, game.id) ? 'favorite' : 'favorite_border'"
                  color="primary" @click="toggleLike(game.id, game.team1.id)" :disable="isGuest" />
                <span class="text-caption">{{ game.team1.likes_count }}</span>
              </div>
            </div>

            <div class="vs-text text-primary">vs</div>

            <div class="team-column">
              <div v-if="game.team2" class="team-clickable" @click="openViewModal(game.team2)">
                <img src="~/assets/logo.png" alt="Team 2" class="team-image" />
                <p class="text-body2">
                  <span class="text-primary">{{ game.team2.name }}</span>
                </p>
              </div>
              <p v-else class="text-body2">
                <span class="text-primary">N/A</span>
              </p>
              <div class="like-section">
                <q-btn dense flat :icon="isLikedByUser(game.team2.liked_by, game.id) ? 'favorite' : 'favorite_border'"
                  color="primary" @click="toggleLike(game.id, game.team2.id)" :disable="isGuest" />
                <span class="text-caption">{{ game.team2.likes_count }}</span>
              </div>
            </div>
          </div>
        </q-card-section>

        <q-card-section class="game-status">
          <template v-if="game.status === 'pending'">
            <q-badge color="secondary" :label="formatDate(game.date, 'D MMMM YYYY')" />
          </template>
          <template v-else-if="game.status === 'completed'">
            <q-badge color="primary" label="Final Score" class="text-white" />
            <div class="score-container">
              <span
                :class="{ 'text-primary text-bold': game.team1.score > game.team2.score, 'text-gray-500': game.team1.score <= game.team2.score }">{{
                  game.team1.score }}</span> |
              <span
                :class="{ 'text-primary text-bold': game.team2.score > game.team1.score, 'text-gray-500': game.team2.score <= game.team1.score }">{{
                  game.team2.score }}</span>
            </div>
          </template>
          <template v-else-if="game.status === 'canceled'">
            <q-badge color="negative" class="text-white" label="Canceled" />
          </template>
        </q-card-section>
      </q-card>
    </div>
  </q-page>

  <ViewTeamModal :viewData="teamData" />
</template>

<script>
import { defineComponent, defineAsyncComponent } from 'vue';
import gameService from 'src/services/gameService';
import teamLikeService from 'src/services/teamLikeService';
import eventService from 'src/services/eventService';
import dateMixin from 'src/utils/mixins/dateMixin';
import handleMedia from 'src/utils/mixins/handleMedia';
import { useUserStore } from 'src/stores/modules/userStore';
import { useModalStore } from 'src/stores/modules/modalStore';
import { USER_ROLES } from 'src/utils/constants';

export default defineComponent({
  components: {
    ViewTeamModal: defineAsyncComponent(() => import('components/Modals/Team/ViewTeam.vue')),
  },
  mixins: [dateMixin, handleMedia],
  data() {
    return {
      games: [],
      search: '',
      selectedEvent: null,
      originalEventOptions: [],
      eventOptions: [],
      selectedStatus: null,
      teamData: [],
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
    isGuest() {
      const userStore = useUserStore();
      return userStore.userData?.role.slug === USER_ROLES.GUEST;
    }
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
    openViewModal(viewData) {
      this.teamData = viewData;
      const modalStore = useModalStore();
      modalStore.setShowViewTeamModal(true);
    },
    async fetchGames() {
      try {
        const response = await gameService.getAllGames({
          search: this.search,
          event: this.selectedEvent,
          status: this.selectedStatus,
        });
        this.games = (response.data.body || []).map(game => ({
          ...game,
        }));
      } catch (error) {
        console.error('Error fetching games:', error);
      }
    },
    async fetchEvents() {
      try {
        const response = await eventService.getAllEvents();
        this.eventOptions = response.data.body || [];
        this.originalEventOptions = [...this.eventOptions];
      } catch (error) {
        console.error('Error fetching events:', error);
      }
    },
    filterEvents(val, update) {
      if (val === '') {
        update(() => {
          this.eventOptions = [...this.originalEventOptions];
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.eventOptions = this.originalEventOptions.filter(event =>
          event.name.toLowerCase().includes(needle)
        );
      });
    },
    debounceFetchGames() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchGames();
      }, 1000);
    },
    isLikedByUser(teamLikes, gameId) {
      const userStore = useUserStore();
      const userId = userStore.userData?.id;

      return teamLikes.some(like => like.user_id === userId && like.game_id === gameId);
    },
    async toggleLike(gameId, teamId) {
      try {
        await teamLikeService.like({
          game_id: gameId,
          team_id: teamId,
        });
        this.fetchGames();

      } catch (error) {
        console.error('Error liking the game:', error);
      }
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

.team-info {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 10px;
  height: 100px;
}

.team-column {
  padding-top: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
  max-width: 200px;
}

.team-column:hover {
  transform: scale(1.05);
}

.team-image {
  width: 60px;
  height: 60px;
  object-fit: cover;
  margin-bottom: 5px;
}

.team-clickable {
  cursor: pointer;
  text-align: center;
}

.vs-text {
  font-size: 1.2rem;
  font-weight: 600;
  margin: 20px 20px 0;
}

.like-section {
  display: flex;
  justify-content: center;
  align-items: center;
}

.game-status {
  text-align: center;
}

.score-container {
  font-size: 1.2rem;
  font-weight: 600;
}
</style>
