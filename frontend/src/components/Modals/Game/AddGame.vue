<template>
  <q-dialog v-model="modalStore.showAddGameModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Game</h3>
      <q-form @submit.prevent>
        <q-input
          v-model="localForm.name"
          label="Game Name"
          outlined
          class="q-mb-md"
        />

        <q-select
          v-model="localForm.event"
          :options="eventOptions"
          class="q-mb-md"
          outlined
          color="primary"
          :clearable="localForm.event !== null"
          emit-value
          map-options
          use-input
          input-debounce="0"
          label="Select Event"
          @filter="filterEvents"
          option-label="name"
          option-value="id"
        />

        <q-select
          v-model="localForm.team1"
          :options="teamOptions"
          class="q-mb-md"
          outlined
          color="primary"
          :clearable="localForm.team1 !== null"
          emit-value
          map-options
          use-input
          input-debounce="0"
          label="Select Team 1"
          :disable="!localForm.event"
          @filter="filterTeams"
          option-label="name"
          option-value="id"
        />

        <q-select
          v-model="localForm.team2"
          :options="teamOptions"
          class="q-mb-md"
          outlined
          color="primary"
          :clearable="localForm.team2 !== null"
          emit-value
          map-options
          use-input
          input-debounce="0"
          label="Select Team 2"
          :disable="!localForm.event"
          @filter="filterTeams"
          option-label="name"
          option-value="id"
        />

        <q-input
          v-model="localForm.date"
          label="Date"
          type="date"
          outlined
          class="q-mb-md"
        />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addGame"></q-btn>
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm"></q-btn>
        </div>
      </q-form>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import gameService from 'src/services/gameService';
import teamService from 'src/services/teamService';
import eventService from 'src/services/eventService';

export default {
  props: {
    fetchGames: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        name: '',
        event: null,
        team1: null,
        team2: null,
        date: '',
      },
      originalEventOptions: [],
      originalTeamOptions: [],
      eventOptions: [],
      teamOptions: [],
      errors: {},
      modalStore: useModalStore(),
    };
  },
  watch: {
    'localForm.event': {
      immediate: true,
      handler(newEvent) {
        if (newEvent) {
          this.fetchTeamOptions(newEvent);
          this.localForm.team1 = null;
          this.localForm.team2 = null;
        } else {
          this.localForm.team1 = null;
          this.localForm.team2 = null;
          this.teamOptions = [];
        }
      }
    }
  },
  mounted() {
    this.fetchEventOptions();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddGameModal(false);
      this.resetForm();
    },
    resetForm() {
      this.localForm = {
        name: '',
        event: null,
        team1: null,
        team2: null,
        date: '',
      };
      this.errors = {};
    },
    async addGame() {
      try {
        const response = await gameService.storeGame({
          name: this.localForm.name,
          event: this.localForm.event,
          team1: this.localForm.team1,
          team2: this.localForm.team2,
          date: this.localForm.date,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchGames();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding game.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchEventOptions() {
      try {
        const response = await eventService.getAllEvents();
        this.eventOptions = response.data.body || [];
        this.originalEventOptions = [...this.eventOptions];

        if (this.eventOptions.length === 0) {
          Notify.create({
            type: 'warning',
            position: 'top',
            textColor: 'white',
            timeout: 10000,
            message: 'No events found. Please add events before creating games.'
          });
        }

      } catch (error) {
        console.error('Error fetching events:', error);
      }
    },
    async fetchTeamOptions() {
      if (!this.localForm.event) return;
      try {
        const response = await teamService.getAllTeams({
          event: this.localForm.event
        });
        this.teamOptions = response.data.body || [];
        this.originalTeamOptions = [...this.teamOptions];

        if (this.teamOptions.length === 0) {
          Notify.create({
            type: 'warning',
            position: 'top',
            textColor: 'white',
            timeout: 10000,
            message: 'No teams found. Please add teams before creating games.'
          });
        }

      } catch (error) {
        console.error('Error fetching teams:', error);
      }
    },
    filterEvents(val, update) {
      if (val === '') {
        update(() => {
          this.eventOptions = this.originalEventOptions;
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.eventOptions = this.originalEventOptions.filter(event => event.name.toLowerCase().includes(needle));
      });
    },
    filterTeams(val, update) {
      if (val === '') {
        update(() => {
          this.teamOptions = this.originalTeamOptions;
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.teamOptions = this.originalTeamOptions.filter(team => team.name.toLowerCase().includes(needle));
      });
    },
  },
};
</script>

<style scoped>
.text-primary {
  color: #3f51b5;
}
</style>

