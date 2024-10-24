<template>
  <q-dialog v-model="modalStore.showEditOfficialModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit Official</h3>
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
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
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
      positionData: [],
      termData: [],
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
  mounted() {
    this.fetchPositionData();
    this.fetchTermData();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditOfficialModal(false);
      this.clearForm();
    },
    clearForm() {
      this.errors = {};
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
        const response = await positionService.getPositions();
        this.positionData = response.data.body || [];
      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
    async fetchTermData() {
      try {
        const response = await termService.getTerms();
        this.termData = response.data.body || [];

        const activeTerm = this.termData.find(term => term.is_active);
        if (activeTerm) {
            this.latestTermId = activeTerm.id;
        }
      } catch (error) {
        console.error('Error fetching terms:', error);
      }
    }
  },
};
</script>
