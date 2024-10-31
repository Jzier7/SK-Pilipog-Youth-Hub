<template>
  <q-dialog v-model="modalStore.showDeleteUserModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-negative pb-4">Delete User</h3>
      <div class="text-primary q-mb-md">
        <p>Are you sure you want to delete the user "<strong>{{ localForm.username }}</strong>"?</p>
        <p class="text-warning">Note: Deleting this user may also remove any associated items.</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Delete" color="negative" class="q-mr-sm" @click="confirmDelete"></q-btn>
        <q-btn label="Cancel" color="grey" @click="closeModal"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { useModalStore } from 'src/stores/modules/modalStore';
import userService from 'src/services/userService';

export default {
  props: {
    fetchUsers: {
      type: Function,
      required: true
    },
    deleteData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localForm: {
        id: this.deleteData.id,
        username: this.deleteData.username,
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    deleteData: {
      immediate: true,
      handler(newValue) {
        this.localForm.id = newValue.id;
        this.localForm.username = newValue.username;
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowDeleteUserModal(false);
    },
    async confirmDelete() {
      try {
        const response = await userService.deleteUser({
          id: this.localForm.id,
        });

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchUsers();
        this.closeModal();
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response.data.message
        });
      }
    }
  }
};
</script>
