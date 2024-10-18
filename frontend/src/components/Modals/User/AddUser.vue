<template>
  <q-dialog v-model="modalStore.showAddUserModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Add User</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.first_name" label="First Name" />
        <CustomInput v-model="localForm.middle_name" label="Middle Name" />
        <CustomInput v-model="localForm.last_name" label="Last Name" />
        <CustomInput v-model="localForm.email" label="Email" type="email" />

        <CustomSelect
          v-model="localForm.purok"
          :options="purokData.map(purok => ({ label: purok.name, value: purok.id }))"
          label="Purok"
          :errorMessage="errors.purok ? errors.purok[0] : ''"
        />

        <CustomInput v-model="localForm.birthdate" label="Birthdate" type="date" />

        <CustomSelect
          v-model="localForm.gender"
          :options="[{ label: 'Male', value: 'Male' }, { label: 'Female', value: 'Female' }]"
          label="Gender"
        />

        <CustomInput v-model="localForm.username" label="Username" />

        <div class="row justify-end">
          <q-btn label="Add" color="primary" @click="addUser"></q-btn>
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm"></q-btn>
        </div>
      </q-form>
    </q-card>
  </q-dialog>

  <q-dialog v-model="showPasswordModal">
    <q-card flat bordered class="q-pa-md text-black">
      <h3 class="text-primary pb-4">User Password</h3>
      <div class="q-mb-md">
        <p>Your created user's password is:</p>
        <q-input
          v-model="generatedPassword"
          readonly
        >
          <template v-slot:append>
            <q-btn round dense flat icon="content_copy" @click="copyPassword" />
          </template>
        </q-input>
        <p class="text-negative q-mt-md">Please take a note of this password. It will only be displayed once!</p>
      </div>
      <div class="row justify-end">
        <q-btn label="Close" color="primary" @click="closePasswordModal"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useModalStore } from 'src/stores/modules/modalStore';
import userService from 'src/services/userService';
import purokService from 'src/services/purokService';

export default {
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
  },
  props: {
    fetchUsers: {
      type: Function,
      required: true
    },
  },
  data() {
    return {
      localForm: {
        first_name: '',
        middle_name: '',
        last_name: '',
        purok: '',
        email: '',
        birthdate: '',
        gender: '',
        username: '',
      },
      purokData: [],
      errors: {},
      modalStore: useModalStore(),
      showPasswordModal: false,
      generatedPassword: '',
    };
  },
  mounted() {
    this.fetchPurok();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowAddUserModal(false);
    },
    closePasswordModal() {
      this.showPasswordModal = false;
      this.clearForm();
    },
    clearForm() {
      this.localForm = {
        first_name: '',
        middle_name: '',
        last_name: '',
        purok: '',
        email: '',
        birthdate: '',
        gender: '',
        username: '',
      };
      this.errors = {};
      this.generatedPassword = '';
    },
    async addUser() {
      try {
        const response = await userService.storeAdmin(this.localForm);
        this.generatedPassword = response.data.body.password;

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message
        });

        this.fetchUsers();
        this.closeModal();
        this.showPasswordModal = true;
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: error.response?.data?.message || 'Error adding user.'
        });
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      }
    },
    async fetchPurok() {
      try {
        const response = await purokService.getAllPurok();
        this.purokData = response.data.body || [];
      } catch (error) {
        console.error('Error fetching purok:', error);
      }
    },
    copyPassword() {
      navigator.clipboard.writeText(this.generatedPassword)
        .then(() => {
          Notify.create({
            type: 'positive',
            position: 'top',
            message: 'Password copied to clipboard!'
          });
        })
        .catch(() => {
          Notify.create({
            type: 'negative',
            position: 'top',
            message: 'Failed to copy password.'
          });
        });
    },
  },
};
</script>

