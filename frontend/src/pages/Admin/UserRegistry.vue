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
          rounded
          outlined
          dense
          color="primary"
          v-model="search"
          placeholder="Search by name or email"
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

        <template v-slot:body-cell-proof_of_voter="props">
          <q-td :props="props" align="center">
            <q-btn
              flat
              dense
              icon="visibility"
              color="primary"
              @click="openProofOfVoterModal(props.row.files)"
            />
          </q-td>
        </template>

        <template v-slot:body-cell-actions="props">
          <q-td :props="props" align="center">
            <q-btn
              flat
              dense
              icon="check"
              color="primary"
              :loading="loading.approve[props.row.id] || false"
              @click="updateUserStatus(props.row.id, status.approved)"
            />
            <q-btn
              flat
              dense
              icon="close"
              color="negative"
              :loading="loading.reject[props.row.id] || false"
              @click="updateUserStatus(props.row.id, status.reject)"
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

    <ProofOfVoterModal :fetchUsers="fetchUsers" :data="userData" />
  </q-page>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import userService from 'src/services/userService';
import { USER_STATUS } from 'src/utils/constants';

export default {
  components: {
    ProofOfVoterModal: defineAsyncComponent(() => import('components/Modals/User/ProofOfVoter.vue')),
  },
  data() {
    return {
      users: [],
      userData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      debounceTimeout: null,
      loading: {
        approve: {},
        reject: {}
      },
      columns: [
        { name: 'firstname', label: 'First Name', align: 'center', field: 'first_name' },
        { name: 'middlename', label: 'Middle Name', align: 'center', field: 'middle_name' },
        { name: 'lastname', label: 'Last Name', align: 'center', field: 'last_name' },
        { name: 'birthdate', label: 'Birthdate', align: 'center', field: 'birthdate' },
        { name: 'gender', label: 'Gender', align: 'center', field: 'gender' },
        { name: 'email', label: 'Email', align: 'center', field: 'email' },
        { name: 'purok', label: 'Purok', align: 'center', field: row => row.purok?.name || 'N/A' },
        { name: 'username', label: 'Username', align: 'center', field: 'username' },
        { name: 'proof_of_voter', label: 'Proof of Voter', align: 'center', field: 'proof_of_voter' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
      status: {
        approved: USER_STATUS.APPROVED,
        reject: USER_STATUS.REJECT
      }
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
        });
        this.users = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
        this.total = response.data.details.total || 0;
      } catch (error) {
        console.error('Error fetching users:', error);
      }
    },
    async updateUserStatus(id, status) {
      if (!this.loading[status]) {
        this.loading[status] = {};
      }

      this.loading[status][id] = true;
      try {
        const response = await userService.updateUserStatus({
          id: id,
          status: status
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchUsers();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error processing request.'
        });
      } finally {
        this.loading[status][id] = false;
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

