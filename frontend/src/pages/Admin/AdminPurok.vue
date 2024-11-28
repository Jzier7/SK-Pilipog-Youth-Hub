<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">PUROK</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-btn label="Add Purok" color="primary" @click="openAddModal" class="q-mr-sm" />
        <q-space />
        <q-input rounded outlined dense color="primary" v-model="search" placeholder="Search by name" class="q-mr-sm">
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </div>

      <q-table flat bordered :rows="puroks" :columns="columns" row-key="id" :pagination="{ rowsPerPage: pageSize }"
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
            <q-btn flat dense icon="edit" color="primary" @click="openEditModal(props.row)" />
            <q-btn flat dense icon="delete" color="negative" @click="openDeleteModal(props.row)" />
          </q-td>
        </template>
      </q-table>

      <div class="row justify-end q-mt-md">
        <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
      </div>
    </div>

    <AddPurokModal :fetchPuroks="fetchPuroks" />
    <EditPurokModal :fetchPuroks="fetchPuroks" :editData="purokData" />
    <DeletePurokModal :fetchPuroks="fetchPuroks" :deleteData="purokData" />
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import purokService from 'src/services/purokService';

export default {
  components: {
    AddPurokModal: defineAsyncComponent(() => import('components/Modals/Purok/AddPurok.vue')),
    EditPurokModal: defineAsyncComponent(() => import('components/Modals/Purok/EditPurok.vue')),
    DeletePurokModal: defineAsyncComponent(() => import('components/Modals/Purok/DeletePurok.vue')),
  },
  data() {
    return {
      puroks: [],
      purokData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      debounceTimeout: null,
      columns: [
        { name: 'name', label: 'Purok Name', align: 'center', field: 'name' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchPuroks();
      }, 1000);
    },
  },
  mounted() {
    this.fetchPuroks();
  },
  methods: {
    openAddModal(addData) {
      this.purokData = addData;

      const modalStore = useModalStore();
      modalStore.setShowAddPurokModal(true);
    },
    openEditModal(editData) {
      this.purokData = editData;

      const modalStore = useModalStore();
      modalStore.setShowEditPurokModal(true);
    },
    openDeleteModal(deleteData) {
      this.purokData = deleteData;

      const modalStore = useModalStore();
      modalStore.setShowDeletePurokModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchPuroks();
    },
    async fetchPuroks() {
      try {
        const response = await purokService.getPaginatedPuroks({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        });
        this.puroks = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching puroks:', error);
      }
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
