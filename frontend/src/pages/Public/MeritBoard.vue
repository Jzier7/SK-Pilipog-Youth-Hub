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
        <q-btn v-show="isAuthorized" :label="isEditing ? 'Submit' : 'Edit Participation Count'" color="primary"
          @click="handleEdit" class="q-mr-sm" />
        <q-space />
        <q-input rounded outlined dense color="primary" v-model="search" placeholder="Search by name or activity"
          class="q-mr-sm">
          <template v-slot:prepend>
            <q-icon name="search" />
          </template>
        </q-input>
      </div>

      <q-table flat bordered :rows="meritData" :columns="columns" row-key="id" :pagination="{ rowsPerPage: pageSize }"
        hide-bottom>
        <template v-slot:body-cell-participation_count="props">
          <q-td :props="props" align="center">
            <template v-if="isEditing">
              <q-btn flat color="negative" icon="remove" @click="decrementCount(props.row)" />
              {{ getParticipationCount(props.row.id) }}
              <q-btn flat color="secondary" icon="add" @click="incrementCount(props.row)" />
            </template>
            <template v-else>
              {{ getParticipationCount(props.row.id) }}
            </template>
          </q-td>
        </template>
        <template v-slot:header="props">
          <q-tr :props="props">
            <q-th v-for="col in props.cols" :key="col.name" :props="props" class="text-primary text-bold">
              {{ col.label }}
            </q-th>
          </q-tr>
        </template>
      </q-table>

      <div class="row justify-end q-mt-md">
        <q-pagination v-model="currentPage" :max="lastPage" @update:model-value="updatePage" direction-links />
      </div>
    </div>
  </q-page>
</template>

<script>
import userService from 'src/services/userService';
import { useUserStore } from 'src/stores/modules/userStore';
import { USER_ROLES } from 'src/utils/constants';

export default {
  data() {
    return {
      meritData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      activeVoter: 1,
      debounceTimeout: null,
      isEditing: false,
      editedCounts: {},
      columns: [
        { name: 'name', label: 'Name', align: 'center', field: row => `${row.first_name} ${row.last_name}` },
        { name: 'participation_count', label: 'Activity Participated', align: 'center' }
      ]
    };
  },
  watch: {
    search() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchMeritData();
      }, 1000);
    }
  },
  computed: {
    isAuthorized() {
      const userStore = useUserStore();
      const role = userStore.userData?.role.slug;
      return role === USER_ROLES.SUPERADMIN || role === USER_ROLES.ADMIN;
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
    handleEdit() {
      this.isEditing = !this.isEditing;
      if (!this.isEditing) {
        this.submitEdits();
      }
    },
    async fetchMeritData() {
      try {
        const response = await userService.getUsers({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize,
          activeVoter: this.activeVoter
        });
        this.meritData = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;

        this.meritData.forEach(item => {
          if (!(item.id in this.editedCounts)) {
            this.editedCounts[item.id] = item.participation_count;
          }
        });

        this.meritData.sort((a, b) => b.participation_count - a.participation_count);
      } catch (error) {
        console.error('Error fetching merit data:', error);
      }
    },
    incrementCount(row) {
      row.participation_count++;
      this.trackEditedCount(row.id, row.participation_count);
    },
    decrementCount(row) {
      if (row.participation_count > 0) {
        row.participation_count--;
        this.trackEditedCount(row.id, row.participation_count);
      }
    },
    trackEditedCount(id, count) {
      this.editedCounts[id] = count;
    },
    getParticipationCount(id) {
      return this.editedCounts[id] !== undefined ? this.editedCounts[id] : 0;
    },
    async submitEdits() {
      const updatedCounts = Object.entries(this.editedCounts).map(([id, count]) => ({ id: parseInt(id), count }));
      if (updatedCounts.length > 0) {
        try {
          await userService.updateUserParticipation(updatedCounts);
          this.fetchMeritData();
        } catch (error) {
          console.error('Error submitting participation counts:', error);
        }
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
