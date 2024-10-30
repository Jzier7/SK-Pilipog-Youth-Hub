<template>
  <div class="q-pa-none">
    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-btn
        label="Add Game"
        color="primary"
        @click="openAddModal"
        class="q-mr-sm"
      />
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

    <q-table
      flat
      bordered
      :rows="games"
      :columns="columns"
      row-key="id"
      :pagination="{ rowsPerPage: pageSize }"
      hide-bottom
    >
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props" class="text-primary text-bold">
            {{ col.label }}
          </q-th>
        </q-tr>
      </template>

      <template v-slot:body-cell-status="props">
        <q-td :props="props" align="center">
          <q-badge
            :color="getStatusColor(props.row.status)"
            :label="props.row.status"
            class="text-white"
          />
        </q-td>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props" align="center">
          <q-btn
            flat
            dense
            icon="edit"
            color="primary"
            @click="openEditModal(props.row)"
          />
          <q-btn
            flat
            dense
            icon="delete"
            color="negative"
            @click="openDeleteModal(props.row)"
          />
          <q-btn
            flat
            dense
            icon="check_circle"
            color="secondary"
            @click="openDecisionModal(props.row)"
          />
        </q-td>
      </template>
    </q-table>

    <div class="row justify-end q-mt-md">
      <q-pagination
        v-model="currentPage"
        :max="lastPage"
        @update:model-value="updatePage"
        direction-links
      />
    </div>
  </div>

  <AddGameModal :fetchGames="fetchGames" />
  <EditGameModal :fetchGames="fetchGames" :editData="gameData" />
  <DeleteGameModal :fetchGames="fetchGames" :deleteData="gameData" />
  <GameResultModal :fetchGames="fetchGames" :resultData="gameData" />
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import gameService from 'src/services/gameService';
import eventService from 'src/services/eventService';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  components: {
    AddGameModal: defineAsyncComponent(() => import('components/Modals/Game/AddGame.vue')),
    EditGameModal: defineAsyncComponent(() => import('components/Modals/Game/EditGame.vue')),
    DeleteGameModal: defineAsyncComponent(() => import('components/Modals/Game/DeleteGame.vue')),
    GameResultModal: defineAsyncComponent(() => import('components/Modals/Game/GameResult.vue')),
  },
  mixins: [dateMixin],
  data() {
    return {
      games: [],
      gameData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      categories: [],
      selectedEvent: null,
      selectedStatus: null,
      events: [],
      columns: [
        { name: 'name', label: 'Game Name', align: 'center', field: 'name' },
        { name: 'event', label: 'Event', align: 'center', field: row => row.event?.name },
        { name: 'date', label: 'Date', align: 'center', field: row => this.formatDate(row.date, 'D MMMM YYYY') },
        { name: 'team1', label: 'Team 1', align: 'center', field: row => row.team1?.name },
        { name: 'team2', label: 'Team 2', align: 'center', field: row => row.team2?.name },
        { name: 'status', label: 'Status', align: 'center', field: 'status' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
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
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddGameModal(true);
    },
    openEditModal(editData) {
      this.gameData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditGameModal(true);
    },
    openDeleteModal(deleteData) {
      this.gameData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeleteGameModal(true);
    },
    openDecisionModal(decisionData) {
      this.gameData = decisionData;
      const modalStore = useModalStore();
      modalStore.setShowGameResultModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchGames();
    },
    debounceFetchGames() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchGames();
      }, 1000);
    },
    async fetchGames() {
      try {
        const response = await gameService.getGames({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          event: this.selectedEvent,
          status: this.selectedStatus,
        });
        this.games = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
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
    getStatusColor(status) {
      switch (status) {
        case 'completed':
          return 'green';
        case 'pending':
          return 'orange';
        case 'canceled':
          return 'red';
        default:
          return 'grey';
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.text-bold {
  font-weight: bold;
}

.q-pagination .q-btn {
  background-color: $primary;
  color: white;
}
</style>

