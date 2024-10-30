<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">MERIT BOARD</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-space />
        <q-input
          rounded
          outlined
          dense
          color="primary"
          v-model="search"
          placeholder="Search by name or activity"
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
        :rows="meritData"
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
  </q-page>
</template>

<script>
import userService from 'src/services/userService';

export default {
  data() {
    return {
      meritData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      debounceTimeout: null,
      columns: [
        { name: 'name', label: 'Name', align: 'center', field: row => `${row.first_name} ${row.last_name}` },
        { name: 'activity', label: 'Activity Participated', align: 'center', field: 'activity_count' }
      ]
    };
  },
  watch: {
    search(newVal) {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchMeritData();
      }, 1000);
    }
  },
  mounted() {
    this.fetchMeritData();
  },
  methods: {
    updatePage(page) {
      this.currentPage = page;
      this.fetchMeritData();
    },
    async fetchMeritData() {
      try {
        const response = await userService.getUserMerits({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
        });
        this.meritData = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
      } catch (error) {
        console.error('Error fetching merit data:', error);
      }
    }
  }
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

