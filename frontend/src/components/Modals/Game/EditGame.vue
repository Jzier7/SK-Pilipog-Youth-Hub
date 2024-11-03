<template>
  <q-dialog v-model="modalStore.showEditGameModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Game</h3>
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
          @input="handleEventChange"
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
          <q-btn label="Update" color="primary" @click="updateGame"></q-btn>
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
    editData: {
      type: Object,
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
    editData: {
      immediate: true,
      handler(newValue) {
        this.populateForm(newValue);
      }
    },
    'localForm.event': {
      immediate: true,
      handler(newEvent) {
        this.handleEventChange(newEvent);
      }
    }
  },
  mounted() {
    this.fetchEventOptions();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditGameModal(false);
      this.resetForm();
    },
    resetForm() {
      this.errors = {};
      this.populateForm(this.editData);
    },
    populateForm(editData) {
      if (editData) {
        this.localForm.name = editData.name;
        this.localForm.event = editData.event_id;
        this.localForm.team1 = editData.team1_id;
        this.localForm.team2 = editData.team2_id;

        if (editData.date) {
          const date = new Date(editData.date + 'Z');
          if (!isNaN(date.getTime())) {
            this.localForm.date = date.toISOString().split('T')[0];
          } else {
            console.error('Invalid date:', editData.date);
            this.localForm.date = '';
          }
        } else {
          this.localForm.date = '';
        }

        if (this.localForm.event) {
          this.fetchTeamOptions(this.localForm.event);
        }
      }
    },
    async updateGame() {
      try {
        const response = await gameService.updateGame({
          id: this.editData.id,
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
          message: error.response?.data?.message || 'Error updating game.'
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
      } catch (error) {
        console.error('Error fetching events:', error);
      }
    },
    async fetchTeamOptions(eventId) {
      if (!eventId) {
        this.teamOptions = [];
        return;
      }
      try {
        const response = await teamService.getAllTeams({ event: eventId });
        this.teamOptions = response.data.body || [];
        this.originalTeamOptions = [...this.teamOptions];
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
    handleEventChange(newEventId) {
      if (newEventId) {
        this.fetchTeamOptions(newEventId).then(() => {
          const selectedTeam1 = this.teamOptions.find(team => team.id === this.localForm.team1);
          const selectedTeam2 = this.teamOptions.find(team => team.id === this.localForm.team2);

          if (!selectedTeam1) {
            this.localForm.team1 = null;
          }
          if (!selectedTeam2) {
            this.localForm.team2 = null;
          }
        });
      } else {
        this.localForm.team1 = null;
        this.localForm.team2 = null;
        this.teamOptions = [];
      }
    },
  },
};
</script>

<style scoped>
.text-primary {
  color: #3f51b5;
}
</style>

