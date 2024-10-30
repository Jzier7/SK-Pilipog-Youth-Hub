<template>
  <q-dialog v-model="modalStore.showEditGameModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Game</h3>
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
          @update:model-value="handleEventChange"
        />

        <CustomSelect
          v-model="localForm.team1"
          :options="teamOptions"
          label="Select Team 1"
          :disable="!localForm.event"
        />

        <CustomSelect
          v-model="localForm.team2"
          :options="teamOptions"
          label="Select Team 2"
          :disable="!localForm.event"
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
    }
  },
  mounted() {
    this.fetchEventOptions();
    if (this.editData.event_id) {
      this.fetchTeamOptions(this.editData.event_id);
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditGameModal(false);
      this.clearForm();
    },
    clearForm() {
      this.errors = {};
    },
    populateForm(editData) {
      if (editData) {
        this.localForm.name = editData.name;
        this.localForm.event = editData.event_id;
        this.localForm.team1 = editData.team1_id;
        this.localForm.team2 = editData.team2_id;

        if (editData.date) {
          const date = new Date(editData.date);
          if (!isNaN(date.getTime())) {
            this.localForm.date = date.toISOString().split('T')[0];
          } else {
            console.error('Invalid date:', editData.date);
            this.localForm.date = '';
          }
        } else {
          this.localForm.date = '';
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
        const response = await eventService.getEvents();
        this.eventOptions = response.data.body.map(event => ({
          label: event.name,
          value: event.id,
        })) || [];
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
        const response = await teamService.getTeams({ event: eventId });
        this.teamOptions = response.data.body.map(team => ({
          label: team.name,
          value: team.id,
        })) || [];
      } catch (error) {
        console.error('Error fetching teams:', error);
      }
    },
    handleEventChange(newEventId) {
      this.fetchTeamOptions(newEventId);
      this.localForm.team1 = null;
      this.localForm.team2 = null;
    },
  },
};
</script>

<style scoped>
.text-primary {
  color: #3f51b5;
}
</style>

