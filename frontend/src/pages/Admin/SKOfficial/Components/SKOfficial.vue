<template>
  <div class="q-pa-none">
    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-btn
        label="Add Official"
        color="primary"
        @click="openAddModal"
        class="q-mr-sm"
      />
      <q-space />
      <q-select
        v-model="selectedPosition"
        :options="positionOptions"
        dense
        outlined
        color="primary"
        :clearable="selectedPosition !== null"
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
        v-model="selectedTerm"
        :options="termOptions"
        dense
        outlined
        color="primary"
        :clearable="selectedTerm !== null"
        emit-value
        map-options
        use-input
        input-debounce="0"
        label="Select Term"
        @filter="filterTerms"
        option-label="date_range"
        option-value="id"
      />
      <q-input
        rounded
        outlined
        dense
        color="primary"
        v-model="search"
        placeholder="Search by name"
        class="q-mr-sm"
      >
        <template v-slot:prepend>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>

    <q-table
      flat
      bordered
      :rows="officials"
      :columns="columns"
      row-key="id"
      :pagination="{ rowsPerPage: pageSize }"
      hide-bottom
    >
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props" class="text-primary text-bold">
            {{ col.label }}
          </q-th>
        </q-tr>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props" align="center">
          <q-btn
            flat
            dense
            icon="edit"
            color="primary"
            @click="openEditModal(props.row)"
          />
          <q-btn
            flat
            dense
            icon="delete"
            color="negative"
            @click="openDeleteModal(props.row)"
          />
        </q-td>
      </template>
    </q-table>

    <div class="row justify-end q-mt-md">
      <q-pagination
        v-model="currentPage"
        :max="lastPage"
        @update:model-value="updatePage"
        direction-links
      />
    </div>
  </div>

  <AddOfficialModal :fetchOfficials="fetchOfficials" />
  <EditOfficialModal :fetchOfficials="fetchOfficials" :editData="officialData" />
  <DeleteOfficialModal :fetchOfficials="fetchOfficials" :deleteData="officialData" />
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import officialService from 'src/services/officialService';
import positionService from 'src/services/positionService';
import termService from 'src/services/termService';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  components: {
    AddOfficialModal: defineAsyncComponent(() => import('components/Modals/Official/AddOfficial.vue')),
    EditOfficialModal: defineAsyncComponent(() => import('components/Modals/Official/EditOfficial.vue')),
    DeleteOfficialModal: defineAsyncComponent(() => import('components/Modals/Official/DeleteOfficial.vue')),
  },
  mixins: [dateMixin],
  data() {
    return {
      officials: [],
      officialData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      positionOptions: [],
      termOptions: [],
      originalPositionOptions: [],
      originalTermOptions: [],
      selectedPosition: null,
      selectedTerm: null,
      columns: [
        { name: 'name', label: 'Name', align: 'center', field: 'name' },
        { name: 'position', label: 'Position', align: 'center', field: row => row.position?.name },
        { name: 'term', label: 'Term', align: 'center', field: row => `${this.formatDate(row.term?.start_date, 'D MMMM YYYY')} - ${this.formatDate(row.term?.end_date, 'D MMMM YYYY')}` },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  watch: {
    search() {
      this.debounceFetchOfficials();
    },
    selectedPosition() {
      this.fetchOfficials();
    },
    selectedTerm() {
      this.fetchOfficials();
    },
  },
  mounted() {
    this.fetchTerms();
    this.fetchOfficials();
    this.fetchPositions();
    this.selectedPosition = null;
  },
  methods: {
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddOfficialModal(true);
    },
    openEditModal(editData) {
      this.officialData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditOfficialModal(true);
    },
    openDeleteModal(deleteData) {
      this.officialData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeleteOfficialModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchOfficials();
    },
    debounceFetchOfficials() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchOfficials();
      }, 1000);
    },
    async fetchOfficials() {
      try {
        const response = await officialService.getPaginatedOfficials({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          position: this.selectedPosition,
          term: this.selectedTerm,
        });
        this.officials = response.data.body.officials || [];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching officials:', error);
      }
    },
    async fetchPositions() {
      try {
        const response = await positionService.getAllPositions();
        this.positionOptions = response.data.body || [];
        this.originalPositionOptions = [...this.positionOptions];

      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    },
    async fetchTerms() {
      try {
        const response = await termService.getAllTerms();
        const terms = response.data.body || [];

        this.termOptions = terms.map(term => ({
          id: term.id,
          date_range: `${this.formatDate(term.start_date, 'D MMMM YYYY')} - ${this.formatDate(term.end_date, 'D MMMM YYYY')}`,
          is_active: term.is_active
        }));
        this.originalTermOptions = [...this.termOptions];

        const activeTerm = this.termOptions.find(term => term.is_active);
        this.selectedTerm = activeTerm ? activeTerm.id : null;
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

<style lang="scss" scoped>
.text-bold {
  font-weight: bold;
}

.q-pagination .q-btn {
  background-color: $primary;
  color: white;
}
</style>
