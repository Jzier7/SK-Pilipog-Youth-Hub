<template>
  <q-dialog v-model="modalStore.showEditOfficialModal" @hide="resetForm">
    <q-card flat bordered class="q-pa-md text-primary" style="width: 700px; max-width: 80vw;">
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

        <CustomCroppa
          :existingImage="oldFiles"
          @imageCropped="updateCroppedImage"
          :stencil-size="{
              width: 300,
              height: 300
            }"
          :stencil-props="{
              handlers: {},
              resizable: false,
              aspectRatio: 1,
            }"
          :isCircular="true"
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
import handleMedia from 'src/utils/mixins/handleMedia';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomCroppa: defineAsyncComponent(() => import('components/Widgets/CustomCroppa.vue')),
  },
  mixins: [dateMixin, handleMedia],
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
        files: this.editData.files || [],
      },
      oldFiles: this.editData.files && this.editData.files.length > 0 ? this.getMediaURL(this.editData.files[0]) : [],
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
        this.oldFiles = newValue.files && newValue.files.length > 0 ? this.getMediaURL(newValue.files[0]) : [];
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
      this.oldFiles = this.getMediaURL(this.editData.files[0]) || [];
    },
    setInitialData(editData) {
      if (editData) {
        this.localForm.name = editData.name || '';
        this.localForm.position = editData.position?.id || '';
        this.localForm.term = editData.term?.id || this.latestTermId;
        this.localForm.files = editData.files ? editData.files : null;
      }
    },
    updateCroppedImage(croppedImage) {
      this.localForm.files = croppedImage;
    },
    async editOfficial() {
      const formData = new FormData();
      formData.append('id', this.editData.id);
      formData.append('name', this.localForm.name);
      formData.append('position', this.localForm.position);
      formData.append('term', this.localForm.term);

      if (this.localForm.files) {
        formData.append('file', this.localForm.files);
      } else {
        this.oldFiles.forEach(file => {
          formData.append('oldFiles[]', file);
        });
      }

      try {
        const response = await officialService.updateOfficial(formData, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
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
    replaceFiles(newFile) {
      if (newFile) {
        this.oldFiles = [];
        this.localForm.files = newFile;
      }
    },
  },
};
</script>
