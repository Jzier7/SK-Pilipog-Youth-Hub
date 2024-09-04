<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">USER REGISTRY</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-space />
        <q-input
          outlined
          dense
          color='primary'
          v-model="search"
          placeholder="Search"
          @input="filterRows"
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
        :rows="filteredRows"
        :columns="columns"
        row-key="id"
      >
        <template v-slot:body-cell-actions="props">
          <q-td :props="props" align="center">
            <q-btn
              flat
              dense
              icon="edit"
              color="primary"
              @click="editUser(props.row)"
            />
            <q-btn
              flat
              dense
              icon="delete"
              color="negative"
              @click="deleteUser(props.row)"
            />
          </q-td>
        </template>
      </q-table>
    </div>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      search: '',
      columns: [
        { name: 'firstname', label: 'First Name', align: 'center', field: 'firstname' },
        { name: 'middlename', label: 'Middle Name', align: 'center', field: 'middlename' },
        { name: 'lastname', label: 'Last Name', align: 'center', field: 'lastname' },
        { name: 'birthdate', label: 'Birthdate', align: 'center', field: 'birthdate' },
        { name: 'gender', label: 'Gender', align: 'center', field: 'gender' },
        { name: 'email', label: 'Email', align: 'center', field: 'email' },
        { name: 'purok', label: 'Purok', align: 'center', field: 'purok' },
        { name: 'proof_of_voter', label: 'Proof of Voter', align: 'center', field: 'proof_of_voter' },
        { name: 'username', label: 'Username', align: 'center', field: 'username' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' }
      ],
      rows: [
        {
          id: 1, firstname: 'John', middlename: 'A.', lastname: 'Doe', birthdate: '1990-01-01',
          gender: 'Male', email: 'john@example.com', purok: '1', proof_of_voter: 'Yes', username: 'john.doe'
        },
        {
          id: 2, firstname: 'Jane', middlename: 'B.', lastname: 'Smith', birthdate: '1992-02-02',
          gender: 'Female', email: 'jane@example.com', purok: '2', proof_of_voter: 'No', username: 'jane.smith'
        }
        // Add more rows as needed
      ]
    };
  },
  computed: {
    filteredRows() {
      const searchLower = this.search.toLowerCase();
      return this.rows.filter(row => {
        return (
          row.firstname.toLowerCase().includes(searchLower) ||
          row.middlename.toLowerCase().includes(searchLower) ||
          row.lastname.toLowerCase().includes(searchLower) ||
          row.email.toLowerCase().includes(searchLower) ||
          row.username.toLowerCase().includes(searchLower)
        );
      });
    }
  },
  methods: {
    filterRows() {
      console.log('Search input:', this.search);
    },
    editUser(row) {
      console.log('Edit button clicked for:', row);
    },
    deleteUser(row) {
      console.log('Delete button clicked for:', row);
    }
  }
};
</script>

<style lang="scss" scoped>
::v-deep .q-table__top {
  background-color: $primary;
  color: white;
}
</style>
