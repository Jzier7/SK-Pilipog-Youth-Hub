<template>
  <q-dialog v-model="modalStore.showAddTeamModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Team</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Team Name" />

        <q-select
          v-model="localForm.event"
          :options="eventOptions"
          label="Select Event"
          class="q-mb-md"
          outlined
          emit-value
          map-options
        />

        <q-select
          v-model="localForm.players"
          :options="userOptions"
          label="Select Players"
          class="q-mb-md"
          outlined
          multiple
          use-input
          map-options
          emit-value
          fill-input
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
              {{ scope.opt.label }}
            </q-chip>
          </template>
        </q-select>

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addTeam"></q-btn>
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
      required: true
    }
  },
  data() {
    return {
      localForm: {
        name: '',
        event: '',
        players: [],
      },
      userOptions: [],
      eventOptions: [],
      modalStore: useModalStore(),
      errors: {},
    };
  },
  mounted() {
    this.fetchUserOptions();
    this.fetchEventOptions();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddTeamModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        name: '',
        event: '',
        players: [],
      };
      this.errors = {};
    },
    async addTeam() {
      try {
        const response = await teamService.storeTeam({
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
          message: error.response?.data?.message || 'Error adding team.',
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchUserOptions() {
      try {
        const response = await userService.getAllUser();
        this.userOptions = response.data.body.map(user => ({
          label: `${user.first_name} ${user.last_name}`,
          value: user.id,
        })) || [];
      } catch (error) {
        console.error('Error fetching users:', error);
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
  },
};
</script>

<style scoped>
.text-primary {
  color: #3f51b5;
}
</style>

