<template>
  <q-dialog v-model="modalStore.showEditOfficialModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Official</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.name" label="Name" />

        <q-select
          v-model="localForm.position"
          :options="positionOptions"
          class="q-mb-md"
          outlined
          color="primary"
          :clearable="localForm.position !== null"
          emit-value
          map-options
          use-input
          input-debounce="0"
          label="Select Position"
          @filter="filterPositions"
          option-label="name"
          option-value="id"
        />

        <q-select
          v-model="localForm.term"
          :options="termOptions"
          class="q-mb-md"
          outlined
          color="primary"
          :clearable="localForm.term !== null"
          emit-value
          map-options
          use-input
          input-debounce="0"
          label="Select Term"
          @filter="filterTerms"
          option-label="date_range"
          option-value="id"
        />

        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="editOfficial"></q-btn>
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
  },
  mixins: [dateMixin],
  props: {
    fetchOfficials: {
      type: Function,
      required: true
    },
    editData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localForm: {
        name: '',
        position: '',
        term: '',
      },
      originalPositionOptions: [],
      originalTermOptions: [],
      positionOptions: [],
      termOptions: [],
      errors: {},
      modalStore: useModalStore(),
      latestTermId: null,
    };
  },
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.setInitialData(newValue);
      }
    }
  },
  mounted() {
    this.fetchPositionData();
    this.fetchTermData();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditOfficialModal(false);
      this.resetForm();
    },
    resetForm() {
      this.errors = {};
      this.setInitialData(this.editData);
    },
    setInitialData(editData) {
      if (editData) {
        this.localForm.name = editData.name || '';
        this.localForm.position = editData.position?.id || '';
        this.localForm.term = editData.term?.id || this.latestTermId;
      }
    },
    async editOfficial() {
      try {
        const response = await officialService.updateOfficial({
          id: this.editData.id,
          name: this.localForm.name,
          position: this.localForm.position,
          term: this.localForm.term,
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
          message: error.response?.data?.message || 'Error editing official.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchPositionData() {
      try {
        const response = await positionService.getAllPositions();
        this.positionOptions = response.data.body || [];
        this.originalPositionOptions = [...this.positionOptions];

      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
    async fetchTermData() {
      try {
        const response = await termService.getAllTerms();
        const terms = response.data.body || [];

        this.termOptions = terms.map(term => ({
          id: term.id,
          date_range: `${this.formatDate(term.start_date, 'D MMMM YYYY')} - ${this.formatDate(term.end_date, 'D MMMM YYYY')}` + (term.is_active ? ' (Active)' : ''),
        }));
        this.originalTermOptions = [...this.termOptions];

        const activeTerm = terms.find(term => term.is_active);
        if (activeTerm) {
            this.latestTermId = activeTerm.id;
        }
      } catch (error) {
        console.error('Error fetching terms:', error);
      }
    },
    filterPositions(val, update) {
      if (val === '') {
        update(() => {
          this.positionOptions = [...this.originalPositionOptions];
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.positionOptions = this.originalPositionOptions.filter(position => position.name.toLowerCase().includes(needle));
      });
    },
    filterTerms(val, update) {
      if (val === '') {
        update(() => {
          this.termOptions = [...this.originalTermOptions];
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.termOptions = this.originalTermOptions.filter(term => term.date_range.toLowerCase().includes(needle));
      });
    },
  },
};
</script>
