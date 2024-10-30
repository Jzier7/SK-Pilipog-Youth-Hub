<template>
  <q-dialog v-model="modalStore.showGameResultModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 500px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Game Result</h3>
      <q-form @submit.prevent>
        <div class="q-mb-md">
          <label class="text-primary">Team 1: {{ team1Name }}</label>
          <q-input
            v-model="localForm.team1Score"
            label="Score"
            type="number"
            outlined
            required
          />
        </div>

        <div class="q-mb-md">
          <label class="text-primary">Team 2: {{ team2Name }}</label>
          <q-input
            v-model="localForm.team2Score"
            label="Score"
            type="number"
            outlined
            required
          />
        </div>

        <CustomSelect
          v-model="localForm.status"
          :options="statusOptions"
          label="Select Status"
          required
        />

        <div class="row justify-end">
          <q-btn label="Submit" color="primary" @click="submitResult"></q-btn>
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

export default {
  components: {
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
  },
  props: {
    fetchGames: {
      type: Function,
      required: true,
    },
    resultData: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      localForm: {
        team1Score: null,
        team2Score: null,
        status: null,
      },
      statusOptions: [
        { label: 'Pending', value: 'pending' },
        { label: 'Completed', value: 'completed' },
        { label: 'Canceled', value: 'canceled' },
      ],
      modalStore: useModalStore(),
    };
  },
  computed: {
    team1Name() {
      return this.resultData.team1?.name || 'Team 1';
    },
    team2Name() {
      return this.resultData.team2?.name || 'Team 2';
    },
  },
  watch: {
    resultData: {
      immediate: true,
      handler(newValue) {
        this.populateForm(newValue);
      },
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowGameResultModal(false);
    },
    populateForm(resultData) {
      if (resultData) {
        this.localForm.team1Score = resultData.team1_score || null;
        this.localForm.team2Score = resultData.team2_score || null;
        this.localForm.status = resultData.status || null;
      }
    },
    async submitResult() {
      try {
        const response = await gameService.updateGameResult({
          id: this.resultData.id,
          team1Score: this.localForm.team1Score,
          team2Score: this.localForm.team2Score,
          status: this.localForm.status,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

        this.fetchGames();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error updating game result.',
        });
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

