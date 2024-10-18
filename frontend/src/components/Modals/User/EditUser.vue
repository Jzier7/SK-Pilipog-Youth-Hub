<template>
  <q-dialog v-model="modalStore.showEditUserModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Edit User</h3>
      <q-form @submit.prevent>
        <CustomInput v-model="localForm.first_name" label="First Name" />
        <CustomInput v-model="localForm.middle_name" label="Middle Name" />
        <CustomInput v-model="localForm.last_name" label="Last Name" />

        <CustomSelect
          v-model="localForm.purok"
          :options="purokData.map(purok => ({ label: purok.name, value: purok.id }))"
          label="Purok"
        />

        <CustomInput v-model="localForm.email" label="Email" type="email" />
        <CustomInput v-model="localForm.birthdate" label="Birthdate" type="date" />
        <CustomSelect
          v-model="localForm.gender"
          :options="[{ label: 'Male', value: 'Male' }, { label: 'Female', value: 'Female' }]"
          label="Gender"
        />
        <CustomInput v-model="localForm.username" label="Username" />
        <div class="row justify-end">
          <q-btn label="Save" color="primary" @click="save"></q-btn>
          <q-btn label="Cancel" color="negative" @click="closeModal" class="q-ml-sm"></q-btn>
        </div>
      </q-form>
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
    editData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localForm: {
        ...this.editData,
        purok: this.editData.purok?.id || null,
      },
      modalStore: useModalStore(),
      purokData: [],
    };
  },
  watch: {
    editData: {
      immediate: true,
      handler(newValue) {
        this.localForm = {
          ...newValue,
          purok: newValue.purok?.id || null,
        };
      }
    }
  },
  mounted() {
    this.fetchPurok();
  },
  methods: {
    closeModal() {
      this.modalStore.setShowEditUserModal(false);
    },
    async save() {
      try {
        const response = await userService.updateUser({
          id: this.localForm.id,
          first_name: this.localForm.first_name,
          middle_name: this.localForm.middle_name,
          last_name: this.localForm.last_name,
          purok: this.localForm.purok,
          birthdate: this.localForm.birthdate,
          gender: this.localForm.gender,
          username: this.localForm.username,
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
          message: error.response?.data?.message || 'Error saving user data.'
        });
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
  },
};
</script>

