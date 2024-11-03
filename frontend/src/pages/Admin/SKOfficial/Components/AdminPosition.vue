<template>
  <div class="q-pa-none">
    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-btn
        label="Add Position"
        color="primary"
        @click="openAddModal"
        class="q-mr-sm"
      />

      <q-space />

      <q-input
        v-model="search"
        rounded
        outlined
        dense
        color="primary"
        placeholder="Search by position name"
        class="q-mt-md"
      >
        <template v-slot:prepend>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>

    <q-table
      flat
      bordered
      :rows="filteredPositions"
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

    <AddPositionModal :fetchPositions="fetchPositions" />
    <EditPositionModal :fetchPositions="fetchPositions" :editData="positionData" />
    <DeletePositionModal :fetchPositions="fetchPositions" :deleteData="positionData" />
  </div>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import positionService from 'src/services/positionService';

export default {
  components: {
    AddPositionModal: defineAsyncComponent(() => import('components/Modals/Position/AddPosition.vue')),
    EditPositionModal: defineAsyncComponent(() => import('components/Modals/Position/EditPosition.vue')),
    DeletePositionModal: defineAsyncComponent(() => import('components/Modals/Position/DeletePosition.vue')),
  },
  data() {
    return {
      positions: [],
      positionData: {},
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      columns: [
        { name: 'name', label: 'Position Name', align: 'center', field: 'name' },
        { name: 'level', label: 'Level', align: 'center', field: 'level' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  computed: {
    filteredPositions() {
      return this.positions.filter(position => {
        return position.name.toLowerCase().includes(this.search.toLowerCase());
      });
    },
  },
  mounted() {
    this.fetchPositions();
  },
  methods: {
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddPositionModal(true);
    },
    openEditModal(position) {
      this.positionData = position;
      const modalStore = useModalStore();
      modalStore.setShowEditPositionModal(true);
    },
    openDeleteModal(position) {
      this.positionData = position;
      const modalStore = useModalStore();
      modalStore.setShowDeletePositionModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
    },
    async fetchPositions() {
      try {
        const response = await positionService.getPaginatedPositions({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        });
        this.positions = response.data.body || [];
        this.lastPage = Math.ceil(this.positions.length / this.pageSize);
      } catch (error) {
        console.error('Error fetching positions:', error);
      }
    }
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>
