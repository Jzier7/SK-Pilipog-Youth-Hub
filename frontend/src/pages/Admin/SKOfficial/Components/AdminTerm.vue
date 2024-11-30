<template>
  <div class="q-pa-none">
    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-btn label="Add Term" color="primary" @click="openAddModal" class="q-mr-sm" />

      <q-space />

      <q-input v-model="search" rounded outlined dense color="primary" placeholder="Search by term date"
        class="q-mt-md">
        <template v-slot:prepend>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>

    <q-table flat bordered :rows="filteredTerms" :columns="columns" row-key="id" :pagination="{ rowsPerPage: pageSize }"
      hide-bottom>
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props" class="text-primary text-bold">
            {{ col.label }}
          </q-th>
        </q-tr>
      </template>

      <template v-slot:body-cell-actions="props">
        <q-td :props="props" align="center">
          <q-btn flat dense icon="edit" color="primary" @click="openEditModal(props.row)" title="Edit" />
          <q-btn flat dense icon="delete" color="negative" @click="openDeleteModal(props.row)" title="Delete" />
          <q-btn flat dense icon="star" color="green" v-if="!props.row.is_active" @click="setActiveTerm(props.row)"
            title="Set Active" />
        </q-td>
      </template>
    </q-table>

    <div class="row justify-center q-mt-md">
      <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
    </div>

    <AddTermModal :fetchTerms="fetchTerms" />
    <EditTermModal :fetchTerms="fetchTerms" :editData="termData" />
    <DeleteTermModal :fetchTerms="fetchTerms" :deleteData="termData" />
  </div>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import termService from 'src/services/termService';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  components: {
    AddTermModal: defineAsyncComponent(() => import('components/Modals/Term/AddTerm.vue')),
    EditTermModal: defineAsyncComponent(() => import('components/Modals/Term/EditTerm.vue')),
    DeleteTermModal: defineAsyncComponent(() => import('components/Modals/Term/DeleteTerm.vue')),
  },
  mixins: [dateMixin],
  data() {
    return {
      terms: [],
      termData: {},
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      columns: [
        { name: 'id', label: 'ID', align: 'center', field: 'id' },
        { name: 'start_date', label: 'Start Date', align: 'center', field: 'start_date' },
        { name: 'end_date', label: 'End Date', align: 'center', field: 'end_date' },
        { name: 'is_active', label: 'Is Active', align: 'center', field: 'is_active', format: val => (val ? 'Yes' : 'No') },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  computed: {
    filteredTerms() {

      const filtered = this.terms.filter(term => {
        const startDateMatch = term.start_date && term.start_date.includes(this.search);
        const endDateMatch = term.end_date && term.end_date.includes(this.search);
        const isActiveMatch = String(term.is_active).toLowerCase().includes(this.search.toLowerCase());

        return startDateMatch || endDateMatch || isActiveMatch;
      });

      return filtered.map(term => ({
        ...term,
        start_date: this.formatDate(term.start_date),
        end_date: this.formatDate(term.end_date)
      }));
    },
  },
  mounted() {
    this.fetchTerms();
  },
  methods: {
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddTermModal(true);
    },
    openEditModal(term) {
      this.termData = term;
      const modalStore = useModalStore();
      modalStore.setShowEditTermModal(true);
    },
    openDeleteModal(term) {
      this.termData = term;
      const modalStore = useModalStore();
      modalStore.setShowDeleteTermModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const options = { year: 'numeric', month: 'long', day: 'numeric' };
      return new Date(dateString).toLocaleDateString('en-US', options);
    },
    async setActiveTerm(term) {
      try {
        const response = await termService.setActiveTerm({ id: term.id });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });
        this.fetchTerms();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error setting term active.',
        });
      }
    },
    async fetchTerms() {
      try {
        const response = await termService.getPaginatedTerms({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        });
        this.terms = response.data.body || [];
        this.lastPage = Math.ceil(this.terms.length / this.pageSize);
      } catch (error) {
        console.error('Error fetching terms:', error);
      }
    },
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>
