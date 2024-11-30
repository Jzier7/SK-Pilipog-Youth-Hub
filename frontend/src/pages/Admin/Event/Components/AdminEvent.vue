<template>
  <div class="q-pa-none">
    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-btn label="Add Event" color="primary" @click="openAddModal" class="q-mr-sm" />
      <q-space />
      <q-select v-model="selectedCategory" :options="categoryOptions" outlined dense color="primary"
        :clearable="selectedCategory !== null" emit-value map-options use-input input-debounce="0"
        label="Select Category" @filter="filterCategories" option-label="name" option-value="id" />
      <q-input rounded outlined dense color="primary" v-model="search" placeholder="Search by event name"
        class="q-mr-sm">
        <template v-slot:prepend>
          <q-icon name="search" />
        </template>
      </q-input>
    </div>

    <q-table flat bordered :rows="events" :columns="columns" row-key="id" :pagination="{ rowsPerPage: pageSize }"
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

    <div class="row justify-center q-mt-md">
      <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
    </div>
  </div>

  <AddEventModal :fetchEvents="fetchEvents" />
  <EditEventModal :fetchEvents="fetchEvents" :editData="eventData" />
  <DeleteEventModal :fetchEvents="fetchEvents" :deleteData="eventData" />
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import eventService from 'src/services/eventService';
import categoryService from 'src/services/categoryService';
import dateMixin from 'src/utils/mixins/dateMixin';

export default {
  components: {
    AddEventModal: defineAsyncComponent(() => import('components/Modals/Event/AddEvent.vue')),
    EditEventModal: defineAsyncComponent(() => import('components/Modals/Event/EditEvent.vue')),
    DeleteEventModal: defineAsyncComponent(() => import('components/Modals/Event/DeleteEvent.vue')),
  },
  mixins: [dateMixin],
  data() {
    return {
      events: [],
      eventData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      selectedCategory: null,
      originalCategoriesOptions: [],
      categoryOptions: [],
      columns: [
        { name: 'name', label: 'Event Name', align: 'center', field: 'name' },
        { name: 'category', label: 'Category', align: 'center', field: row => row.category?.name },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  watch: {
    search() {
      this.debounceFetchEvents();
    },
    selectedCategory() {
      this.fetchEvents();
    },
  },
  mounted() {
    this.fetchEvents();
    this.fetchCategories();
  },
  methods: {
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddEventModal(true);
    },
    openEditModal(editData) {
      this.eventData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditEventModal(true);
    },
    openDeleteModal(deleteData) {
      this.eventData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeleteEventModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchEvents();
    },
    debounceFetchEvents() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchEvents();
      }, 1000);
    },
    async fetchEvents() {
      try {
        const response = await eventService.getPaginatedEvents({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          category: this.selectedCategory,
        });
        this.events = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching events:', error);
      }
    },
    async fetchCategories() {
      try {
        const response = await categoryService.getAllCategories();
        this.categoryOptions = response.data.body || [];
        this.originalCategoriesOptions = [...this.categoryOptions];
      } catch (error) {
        console.error('Error fetching categories:', error);
      }
    },
    filterCategories(val, update) {
      if (val === '') {
        update(() => {
          this.categoryOptions = [...this.originalCategoriesOptions];
        });
        return;
      }

      update(() => {
        const needle = val.toLowerCase();
        this.categoryOptions = this.originalCategoriesOptions.filter(category => category.name.toLowerCase().includes(needle));
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
