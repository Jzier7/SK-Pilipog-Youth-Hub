<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full">
        <q-form @submit="onSubmit" class="row shadow-xl md:w-2/4 w-full">
          <div class="col-12 col-md-5 q-pa-lg bg-primary text-white">
            <div class="full-height flex flex-center column q-py-xl">
              <h4 class="text-center">Remembered Your Details?</h4>
              <p class="text-center">Sign in to your account now!</p>
              <span class="w-3/4">
                <CustomButton label="Sign in" btnClass="secondary-btn" type="reset" :onClick="navigateToSignIn" />
              </span>
            </div>
          </div>

          <div class="col-12 col-md-7 q-pa-lg">
            <div class="full-height flex flex-center column q-py-sm">
              <h2 class="text-center text-primary">Forgot Password</h2>

              <div v-if="!userExists" class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full q-pt-md">
                <CustomInput :errorMessage="errors.username ? errors.username[0] : ''" v-model="form.username"
                  label="Username" inputClass="col-span-2" />
                <CustomInput :errorMessage="errors.first_name ? errors.first_name[0] : ''" v-model="form.first_name"
                  label="First Name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.middle_name ? errors.middle_name[0] : ''" v-model="form.middle_name"
                  label="Middle Name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.last_name ? errors.last_name[0] : ''" v-model="form.last_name"
                  label="Last Name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.birthdate ? errors.birthdate[0] : ''" v-model="form.birthdate"
                  label="Birthdate" type="date" inputClass="col-span-2 md:col-span-1" />
                <CustomSelect v-model="form.gender" :options="genderOptions" label="Gender"
                  :errorMessage="errors.gender ? errors.gender[0] : ''" inputClass="col-span-2" />
              </div>

              <div v-else class="grid grid-cols-1 gap-4 w-full q-pt-md">
                <CustomInput :errorMessage="errors.password ? errors.password[0] : ''" v-model="passwordForm.password"
                  label="New Password" type="password" inputClass="col-span-2" />
                <CustomInput :errorMessage="errors.confirm_password ? errors.confirm_password[0] : ''"
                  v-model="passwordForm.confirm_password" label="Confirm Password" type="password"
                  inputClass="col-span-2" />
              </div>

              <CustomButton :label="userExists ? 'Update Password' : 'Submit'" type="submit" />
            </div>
          </div>
        </q-form>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import authService from '../../services/authService';

export default {
  name: 'ForgotPassword',
  components: {
    CustomButton: defineAsyncComponent(() => import('components/Widgets/CustomButton.vue')),
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
  },
  data() {
    return {
      form: {
        username: '',
        first_name: '',
        middle_name: '',
        last_name: '',
        birthdate: '',
        gender: '',
      },
      passwordForm: {
        password: '',
        confirm_password: '',
        userId: null,
      },
      genderOptions: [
        { label: 'Select Gender', value: '' },
        { label: 'Male', value: 'male' },
        { label: 'Female', value: 'female' },
      ],
      userExists: false,
      errors: {},
    };
  },
  methods: {
    navigateToSignIn() {
      this.$router.push('/');
    },
    async onSubmit() {
      if (!this.userExists) {
        try {
          const response = await authService.forgotPassword(this.form);

          Notify.create({
            type: 'positive',
            position: 'top',
            message: response.message,
          });

          this.passwordForm.userId = response.body.id;
          this.userExists = true;
        } catch (err) {
          const { data } = err.response;
          this.errors = data.errors || {};

          if (err.response.status === 404) {
            Notify.create({
              type: 'negative',
              position: 'top',
              message: data.message,
            });
          }
        }
      } else {
        try {
          const payload = {
            ...this.passwordForm,
            userId: this.passwordForm.userId,
          };
          const response = await authService.updatePassword(payload);

          Notify.create({
            type: 'positive',
            position: 'top',
            message: response.message,
          });

          this.$router.push('/');
        } catch (err) {
          const { data } = err.response;
          this.errors = data.errors || {};
        }
      }
    },
  },
};
</script>
