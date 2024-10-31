<template>
  <q-page padding>
    <div class="q-mb-md">
      <q-toolbar class="q-pa-none">
        <q-toolbar-title>
          <h3 class="text-primary">MY ACCOUNT</h3>
        </q-toolbar-title>
      </q-toolbar>
    </div>

    <div class="q-pa-none">
      <div class="q-mb-md q-gutter-sm flex items-center">
        <q-btn
          color="primary"
          label="Update Info"
          @click="enableEditing"
          v-if="!isEditing"
        />
        <q-btn
          color="secondary"
          label="Save"
          @click="updateUserInfo"
          v-if="isEditing"
        />
        <q-btn
          color="grey"
          label="Cancel"
          @click="cancelEditing"
          v-if="isEditing"
        />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        <CustomInput
          v-model="userData.first_name"
          label="First Name"
          inputClass="col-span-2 md:col-span-1"
          :disable="!isEditing"
          :errorMessage="errors.first_name ? errors.first_name[0] : ''"
        />
        <CustomInput
          v-model="userData.last_name"
          label="Last Name"
          inputClass="col-span-2 md:col-span-1"
          :disable="!isEditing"
          :errorMessage="errors.last_name ? errors.last_name[0] : ''"
        />
        <CustomInput
          v-model="userData.middle_name"
          label="Middle Name"
          inputClass="col-span-2 md:col-span-1"
          :disable="!isEditing"
          :errorMessage="errors.middle_name ? errors.middle_name[0] : ''"
        />
        <CustomInput
          v-model="userData.birthdate"
          label="Birthdate"
          type="date"
          inputClass="col-span-2 md:col-span-1"
          :disable="!isEditing"
          :errorMessage="errors.birthdate ? errors.birthdate[0] : ''"
        />
        <CustomSelect
          v-model="userData.gender"
          :options="genderOptions"
          label="Gender"
          inputClass="col-span-2 md:col-span-1"
          :disable="!isEditing"
          :errorMessage="errors.gender ? errors.gender[0] : ''"
        />
        <CustomSelect
          v-model="userData.purok"
          :options="purokData.map(purok => ({ label: purok.name, value: purok.id }))"
          label="Purok"
          :disable="!isEditing"
          :errorMessage="errors.purok ? errors.purok[0] : ''"
        />
        <CustomInput
          v-model="userData.email"
          label="Email"
          type="email"
          inputClass="col-span-2 md:col-span-1"
          :disable="!isEditing"
          :errorMessage="errors.email ? errors.email[0] : ''"
        />
        <CustomInput
          v-model="userData.username"
          label="Username"
          inputClass="col-span-2 md:col-span-1"
          :disable="!isEditing"
          :errorMessage="errors.username ? errors.username[0] : ''"
        />
      </div>
    </div>
  </q-page>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import { useUserStore } from 'src/stores/modules/userStore';
import purokService from 'src/services/purokService';

export default {
  name: 'UserInfo',
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
  },
  data() {
    return {
      userData: {
        purok: null,
        purok_id: null,
      },
      purokData: [],
      genderOptions: [
        { label: 'Male', value: 'male' },
        { label: 'Female', value: 'female' }
      ],
      isEditing: false,
      errors: {},
    };
  },
  methods: {
    enableEditing() {
      this.isEditing = true;
      this.errors = {};
    },
    cancelEditing() {
      this.isEditing = false;
      this.errors = {};
      this.fetchUserData();
    },
    async fetchUserData() {
      const userStore = useUserStore();
      this.userData = { ...userStore.userData, purok: userStore.userData.purok_id };
    },
    async fetchPurok() {
      try {
        const response = await purokService.getAllPurok();
        this.purokData = response.data.body || [];
      } catch (error) {
        console.error('Error fetching purok:', error);
      }
    },
    async updateUserInfo() {
      const userStore = useUserStore();
      this.userData.purok_id = this.userData.purok;
      const { success, response, error } = await userStore.updateUser(this.userData);
      if (success) {
        this.isEditing = false;

        Notify.create({
          type: 'positive',
          position: 'top',
          message: response.data.message,
        });

      } else {
        this.errors = error;
      }
    },
  },
  mounted() {
    this.fetchPurok();
    this.fetchUserData();
  },
};
</script>

<style lang="scss" scoped>
.q-page {
  padding: 20px;
}
</style>

