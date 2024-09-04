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
        <q-btn color="primary" label="Update Info" @click="updateUserInfo" />
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
        <CustomInput
          v-model="user.first_name"
          label="First Name"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.last_name"
          label="Last Name"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.middle_name"
          label="Middle Name"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.birthdate"
          label="Birthdate"
          type="date"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.gender"
          label="Gender"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.purok"
          label="Purok"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomSelect
          v-model="user.active_voter"
          label="Active Voter?"
          :options="['Yes', 'No']"
          selectClass="col-span-2 md:col-span-1"
        />
        <CustomUploader
          v-model="user.uploadedFiles"
          label="Upload Image"
          uploaderClass="col-span-2"
        />
        <CustomInput
          v-model="user.email"
          label="Email"
          type="email"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.username"
          label="Username"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.password"
          label="Password"
          type="password"
          inputClass="col-span-2 md:col-span-1"
        />
        <CustomInput
          v-model="user.confirm_password"
          label="Confirm Password"
          type="password"
          inputClass="col-span-2 md:col-span-1"
        />
      </div>
    </div>
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import userService from '../../services/userService';

export default {
  name: 'UserInfo',
  components: {
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
    CustomUploader: defineAsyncComponent(() => import('components/Widgets/CustomUploader.vue')),
  },
  data() {
    return {
      user: {
        first_name: '',
        last_name: '',
        middle_name: '',
        birthdate: '',
        gender: '',
        purok: '',
        active_voter: '',
        uploadedFiles: [],
        email: '',
        username: '',
        password: '',
        confirm_password: ''
      }
    };
  },
  methods: {
    async fetchUserInfo() {
      try {
        const userInfo = await userService.getUserInfo();
        this.user = userInfo;
      } catch (err) {
        console.error('Error fetching user information:', err);
      }
    },
    async updateUserInfo() {
      try {
        const response = await userService.updateUser(this.user);
        console.log('User information updated successfully:', response);
      } catch (err) {
        console.error('Error updating user information:', err);
      }
    }
  },
  created() {
    this.fetchUserInfo();
  }
}
</script>

<style lang="scss" scoped>
.q-page {
  padding: 20px;
}
</style>

