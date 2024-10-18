<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">USER ACCOUNTS</h3>
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
          placeholder="Search by name or email"
          @input="filterUsers"
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
        :rows="users"
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

    <EditUserModal :fetchUsers="fetchUsers" :editData="userData" />
    <DeleteUserModal :fetchUsers="fetchUsers" :deleteData="userData" />
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import userService from 'src/services/userService';

export default {
  components: {
    EditUserModal: defineAsyncComponent(() => import('components/Modals/User/EditUser.vue')),
    DeleteUserModal: defineAsyncComponent(() => import('components/Modals/User/DeleteUser.vue')),
  },
  data() {
    return {
      users: [],
      userData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      activeVoter: 1,
      total: 0,
      debounceTimeout: null,
      columns: [
        { name: 'firstname', label: 'First Name', align: 'center', field: 'first_name' },
        { name: 'middlename', label: 'Middle Name', align: 'center', field: 'middle_name' },
        { name: 'lastname', label: 'Last Name', align: 'center', field: 'last_name' },
        { name: 'birthdate', label: 'Birthdate', align: 'center', field: 'birthdate' },
        { name: 'gender', label: 'Gender', align: 'center', field: 'gender' },
        { name: 'email', label: 'Email', align: 'center', field: 'email' },
        { name: 'purok', label: 'Purok', align: 'center', field: row => row.purok?.name || 'N/A' },
        { name: 'username', label: 'Username', align: 'center', field: 'username' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  watch: {
    search(newVal) {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchUsers();
      }, 1000);
    },
  },
  mounted() {
    this.fetchUsers();
  },
  methods: {
    openEditModal(editData) {
      this.userData = editData

      const modalStore = useModalStore();
      modalStore.setShowEditUserModal(true);
    },
    openDeleteModal(deleteData) {
      this.userData = deleteData;

      const modalStore = useModalStore();
      modalStore.setShowDeleteUserModal(true);
    },
    openProofOfVoterModal(data) {
      this.userData = data;

      const modalStore = useModalStore();
      modalStore.setShowProofOfVoterModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchUsers();
    },
    async fetchUsers() {
      try {
        const response = await userService.getAllUser({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          activeVoter: this.activeVoter
        });
        this.users = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching users:', error);
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

