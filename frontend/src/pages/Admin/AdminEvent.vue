<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">EVENT</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-btn color="primary" label="Add" icon="add" @click="addTeam" />
      </div>

      <q-table
        flat
        bordered
        :title="tableTitle"
        :rows="filteredRows"
        :columns="columns"
        row-key="id"
        :visible-columns="visibleColumns"
      >
        <template v-slot:top>
          <div>
            <h3>{{ leagueName }}</h3>
            <span class="text-[13px]">{{ formattedDateRange }}</span>
          </div>
          <q-space />
          <q-select
            v-model="selectedCategory"
            outlined
            bg-color="white"
            dense
            options-dense
            :options="categories"
            option-value="value"
            option-label="label"
            style="min-width: 150px"
            placeholder="Select Category"
            @change="filterRows"
          />
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      visibleColumns: ['id', 'name'],
      selectedCategory: 'All',
      categories: ['All', 'Basketball', 'Volleyball', 'Mobile Legends'],
      columns: [
        { name: 'id', label: 'ID', align: 'center', field: 'id' },
        { name: 'name', label: 'Team Name', align: 'center', field: 'name' }
      ],
      rows: [
        { id: 1, name: 'Team A', category: 'Basketball' },
        { id: 2, name: 'Team B', category: 'Basketball' },
        { id: 3, name: 'Team C', category: 'Volleyball' },
        { id: 4, name: 'Team D', category: 'Volleyball' },
        { id: 5, name: 'Team E', category: 'Mobile Legends' },
        { id: 6, name: 'Team F', category: 'Mobile Legends' }
      ],
      leagueName: 'Summer League',
      startDate: 'March 24, 2024',
      endDate: 'April 24, 2024'
    }
  },
  computed: {
    tableTitle() {
      return `${this.leagueName} Teams`;
    },
    formattedDateRange() {
      return `${this.startDate} - ${this.endDate}`;
    },
    filteredRows() {
      if (this.selectedCategory === 'All') {
        return this.rows;
      }
      return this.rows.filter(row => row.category === this.selectedCategory);
    }
  },
  methods: {
    filterRows() {
      console.log('Filter method called. Selected Category:', this.selectedCategory);
    },
    addTeam() {
      console.log('Add button clicked');
    }
  }
}
</script>

<style lang="scss" scoped>
::v-deep .q-table__top {
  background-color: $primary;
  color: white;
}
</style>
