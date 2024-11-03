<template>
  <div class="q-pa-none">
    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-btn
        label="Add Team"
        color="primary"
        @click="openAddModal"
        class="q-mr-sm"
      />
      <q-space />
      <q-input
        rounded
        outlined
        dense
        color="primary"
        v-model="search"
        placeholder="Search by team name"
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
      :rows="teams"
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

          <q-btn
            flat
            dense
            icon="visibility"
            color="secondary"
            @click="openViewModal(props.row)"
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

  <AddTeamModal :fetchTeams="fetchTeams" />
  <EditTeamModal :fetchTeams="fetchTeams" :editData="teamData" />
  <DeleteTeamModal :fetchTeams="fetchTeams" :deleteData="teamData" />
  <ViewTeamModal :viewData="teamData" />
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import teamService from 'src/services/teamService';

export default {
  components: {
    AddTeamModal: defineAsyncComponent(() => import('components/Modals/Team/AddTeam.vue')),
    EditTeamModal: defineAsyncComponent(() => import('components/Modals/Team/EditTeam.vue')),
    DeleteTeamModal: defineAsyncComponent(() => import('components/Modals/Team/DeleteTeam.vue')),
    ViewTeamModal: defineAsyncComponent(() => import('components/Modals/Team/ViewTeam.vue')),
  },
  data() {
    return {
      teams: [],
      teamData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      columns: [
        { name: 'name', label: 'Team Name', align: 'center', field: 'name' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  watch: {
    search() {
      this.debounceFetchTeams();
    },
  },
  mounted() {
    this.fetchTeams();
  },
  methods: {
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddTeamModal(true);
    },
    openEditModal(editData) {
      this.teamData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditTeamModal(true);
    },
    openDeleteModal(deleteData) {
      this.teamData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeleteTeamModal(true);
    },
    openViewModal(viewData) {
      this.teamData = viewData;
      const modalStore = useModalStore();
      modalStore.setShowViewTeamModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchTeams();
    },
    debounceFetchTeams() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchTeams();
      }, 1000);
    },
    async fetchTeams() {
      try {
        const response = await teamService.getPaginatedTeams({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize
        });
        this.teams = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
      } catch (error) {
        console.error('Error fetching teams:', error);
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
