<template>
  <q-dialog v-model="modalStore.showAddOfficialModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add Official</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Name" />

        <CustomSelect
          v-model="localForm.position"
          :options="positionData.map(position => ({ label: position.name, value: position.id }))"
          label="Position"
        />

        <CustomSelect
          v-model="localForm.term"
          :options="formattedTermOptions"
          label="Term"
        />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addOfficial"></q-btn>
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
import officialService from 'src/services/officialService';
import positionService from 'src/services/positionService';
import termService from 'src/services/termService';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
  },
  mixins: [dateMixin],
  props: {
    fetchOfficials: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        name: '',
        position: '',
        term: '',
      },
      positionData: [],
      termData: [],
      errors: {},
      modalStore: useModalStore(),
      latestTermId: null,
    };
  },
  mounted() {
    this.fetchPositionData();
    this.fetchTermData();
  },
  computed: {
    formattedTermOptions() {
      return this.termData.map(term => {
        const label = `${this.formatDate(term.start_date, 'D MMMM YYYY')} - ${this.formatDate(term.end_date, 'D MMMM YYYY')}`;
        return {
          label: term.is_active ? `${label} (Active)` : label,
          value: term.id,
        };
      });
    },
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddOfficialModal(false);
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        name: '',
        position: '',
        term: '', // Reset term
      };
      this.errors = {};
    },
    async addOfficial() {
      try {
        const response = await officialService.storeOfficial({
          name: this.localForm.name,
          position: this.localForm.position,
          term: this.localForm.term
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchOfficials();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding official.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchPositionData() {
      try {
        const response = await positionService.getPositions();
        this.positionData = response.data.body || [];

        if (this.positionData.length === 0) {
          Notify.create({
            type: 'warning',
            position: 'top',
            textColor: 'white',
            timeout: 10000,
            message: 'No positions found. Please add positions before creating officials.'
          });
        }

      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
    async fetchTermData() {
      try {
        const response = await termService.getTerms();
        this.termData = response.data.body || [];

        if (this.termData.length === 0) {
          Notify.create({
            type: 'warning',
            position: 'top',
            textColor: 'white',
            timeout: 10000,
            message: 'No terms found. Please add terms before creating official.'
          });
        }

        const activeTerm = this.termData.find(term => term.is_active);
        if (activeTerm) {
            this.localForm.term = activeTerm.id;
        }
      } catch (error) {
        console.error('Error fetching terms:', error);
      }
    }
  },
};
</script>

