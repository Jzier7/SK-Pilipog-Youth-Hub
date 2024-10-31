<template>
  <q-dialog v-model="modalStore.showAddGameModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Game</h3>
      <q-form @submit.prevent>
        <q-input
          v-model="localForm.name"
          label="Game Name"
          outlined
          class="q-mb-md"
        />

        <CustomSelect
          v-model="localForm.event"
          :options="eventOptions"
          label="Select Event"
          @update:model-value="fetchTeamOptions"
        />

        <CustomSelect
          class="q-mb-md"
          v-model="localForm.team1"
          :options="teamOptions"
          label="Select Team 1"
          :disable="!localForm.event"
          hint="Select an event first"
          outlined
        />

        <CustomSelect
          class="q-mb-md"
          v-model="localForm.team2"
          :options="teamOptions"
          label="Select Team 2"
          :disable="!localForm.event"
          hint="Select an event first"
          outlined
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
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import gameService from 'src/services/gameService';
import teamService from 'src/services/teamService';
import eventService from 'src/services/eventService';

export default {
  components: {
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
  },
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
        this.fetchTeamOptions();
        if (newEvent) {
          this.localForm.team1 = null;
          this.localForm.team2 = null;
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
      this.clearForm();
    },
    clearForm() {
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
        const response = await eventService.getEvents();
        this.eventOptions = response.data.body.map(event => ({
          label: event.name,
          value: event.id,
        })) || [];

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
        const response = await teamService.getTeams({
          event: this.localForm.event
        });
        this.teamOptions = response.data.body.map(team => ({
          label: team.name,
          value: team.id,
        })) || [];

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
  },
};
</script>

<style scoped>
.text-primary {
  color: #3f51b5;
}
</style>

