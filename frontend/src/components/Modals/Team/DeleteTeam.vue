<template>
  <q-dialog v-model="modalStore.showDeleteTeamModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete Team</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the team "<strong>{{ localForm.name }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this team may also remove any associated games.</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Delete" color="negative" class="q-mr-sm" @click="confirmDelete"></q-btn>
        <q-btn label="Cancel" color="grey" @click="closeModal"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import teamService from 'src/services/teamService';

export default {
  props: {
    fetchTeams: {
      type: Function,
      required: true
    },
    deleteData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localForm: {
        id: this.deleteData.id,
        name: this.deleteData.name,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue.id;
        this.localForm.name = newValue.name;
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteTeamModal(false);
    },
    async confirmDelete() {
      try {
        const response = await teamService.deleteTeam({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchTeams();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error deleting team.'
        });
      }
    }
  }
};
</script>

<style scoped>
.text-negative {
  color: #f44336;
}
.text-primary {
  color: #3f51b5;
}
</style>

