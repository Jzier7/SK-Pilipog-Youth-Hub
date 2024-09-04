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

      <q-table
        flat
        bordered
        :title="tableTitle"
        :rows="filteredRows"
        :columns="columns"
        row-key="id"
      >
        <template v-slot:top>
          <div>
            <h3>Activity Leaderboard</h3>
            <span class="text-[13px]">{{ formattedDateRange }}</span>
          </div>
          <q-space />
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      columns: [
        { name: 'id', label: 'ID', align: 'center', field: 'id' },
        { name: 'name', label: 'Name', align: 'left', field: 'name' },
        { name: 'activityCount', label: 'Activities Participated', align: 'center', field: 'activityCount' }
      ],
      users: [
        { id: 1, name: 'Juan Dela Cruz', activityCount: 5 },
        { id: 2, name: 'Maria Santos', activityCount: 8 },
        { id: 3, name: 'Pedro Reyes', activityCount: 3 },
        { id: 4, name: 'Josefa Garcia', activityCount: 10 }
      ],
      leagueName: 'Activity League',
      startDate: 'January 1, 2024',
      endDate: 'December 31, 2024'
    }
  },
  computed: {
    tableTitle() {
      return `${this.leagueName} Leaderboard`;
    },
    formattedDateRange() {
      return `${this.startDate} - ${this.endDate}`;
    },
    filteredRows() {
      return [...this.users].sort((a, b) => b.activityCount - a.activityCount);
    }
  },
  methods: {
    addUser() {
      console.log('Add User button clicked');
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

