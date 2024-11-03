<template>
  <q-dialog v-model="modalStore.showEditTeamModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Team</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Team Name" />

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
          v-model="localForm.players"
          :options="playerOptions"
          label="Select Players"
          class="q-mb-md"
          outlined
          multiple
          use-input
          input-debounce="0"
          map-options
          emit-value
          fill-input
          @filter="filterPlayers"
          option-label="name"
          option-value="id"
        >
          <template v-slot:selected-item="scope">
            <q-chip
              removable
              dense
              @remove="scope.removeAtIndex(scope.index)"
              :tabindex="scope.tabindex"
              color="white"
              text-color="secondary"
              class="q-ma-none"
            >
              {{ scope.opt.name }}
            </q-chip>
          </template>
        </q-select>

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="editTeam"></q-btn>
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
import teamService from 'src/services/teamService';
import userService from 'src/services/userService';
import eventService from 'src/services/eventService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
  },
  props: {
    fetchTeams: {
      type: Function,
      required: true,
    },
    editData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localForm: {
        name: '',
        event: '',
        players: [],
      },
      originalEventOptions: [],
      originalPlayerOptions: [],
      playerOptions: [],
      eventOptions: [],
      modalStore: useModalStore(),
      errors: {},
    };
  },
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.setInitialData(newValue);
      },
    },
  },
  mounted() {
    this.fetchUserOptions();
    this.fetchEventOptions();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditTeamModal(false);
      this.resetForm();
    },
    resetForm() {
      this.errors = {};
      this.setInitialData(this.editData)
    },
    setInitialData(editData) {
      if (editData) {
        this.localForm.name = editData.name || '';
        this.localForm.event = editData.event_id || '';
        this.localForm.players = editData.players?.map(player => player.id) || [];
      }
    },
    async editTeam() {
      try {
        const response = await teamService.updateTeam({
          id: this.editData.id,
          name: this.localForm.name,
          event: this.localForm.event,
          players: this.localForm.players,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.closeModal();
        this.fetchTeams();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error editing team.',
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchUserOptions() {
      try {
        const response = await userService.getPlayers();
        this.playerOptions = response.data.body || [];
        this.originalPlayerOptions = [...this.playerOptions];

      } catch (error) {
        console.error('Error fetching users:', error);
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
    filterPlayers(val, update) {
      if (val === '') {
        update(() => {
          this.playerOptions = this.originalPlayerOptions;
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.playerOptions = this.originalPlayerOptions.filter(player => player.name.toLowerCase().includes(needle));
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

