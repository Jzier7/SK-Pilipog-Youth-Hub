<template>
  <div class="q-pa-none">
    <div class="q-mb-md q-gutter-sm flex items-center">
      <q-btn
        label="Add Category"
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
        placeholder="Search by category name"
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
      :rows="categories"
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

  <AddCategoryModal :fetchCategories="fetchCategories" />
  <EditCategoryModal :fetchCategories="fetchCategories" :editData="categoryData" />
  <DeleteCategoryModal :fetchCategories="fetchCategories" :deleteData="categoryData" />
</template>

<script>
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import categoryService from 'src/services/categoryService';

export default {
  components: {
    AddCategoryModal: defineAsyncComponent(() => import('components/Modals/Category/AddCategory.vue')),
    EditCategoryModal: defineAsyncComponent(() => import('components/Modals/Category/EditCategory.vue')),
    DeleteCategoryModal: defineAsyncComponent(() => import('components/Modals/Category/DeleteCategory.vue')),
  },
  data() {
    return {
      categories: [],
      categoryData: [],
      search: '',
      currentPage: 1,
      pageSize: 12,
      lastPage: 1,
      total: 0,
      columns: [
        { name: 'name', label: 'Category Name', align: 'center', field: 'name' },
        { name: 'actions', label: 'Actions', align: 'center', field: 'actions' },
      ],
    };
  },
  watch: {
    search() {
      this.debounceFetchCategories();
    },
  },
  mounted() {
    this.fetchCategories();
  },
  methods: {
    openAddModal() {
      const modalStore = useModalStore();
      modalStore.setShowAddCategoryModal(true);
    },
    openEditModal(editData) {
      this.categoryData = editData;
      const modalStore = useModalStore();
      modalStore.setShowEditCategoryModal(true);
    },
    openDeleteModal(deleteData) {
      this.categoryData = deleteData;
      const modalStore = useModalStore();
      modalStore.setShowDeleteCategoryModal(true);
    },
    updatePage(page) {
      this.currentPage = page;
      this.fetchCategories();
    },
    debounceFetchCategories() {
      clearTimeout(this.debounceTimeout);
      this.debounceTimeout = setTimeout(() => {
        this.fetchCategories();
      }, 1000);
    },
    async fetchCategories() {
      try {
        const response = await categoryService.getAllCategories({
          search: this.search,
          currentPage: this.currentPage,
          pageSize: this.pageSize
        });
        this.categories = response.data.body || [];
        this.lastPage = response.data.details.last_page || 1;
      } catch (error) {
        console.error('Error fetching categories:', error);
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

