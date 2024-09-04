<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full">
        <q-form
          @submit="onSubmit"
          @reset="onReset"
          class="row shadow-xl md:w-2/4 w-full"
        >
          <div class="col-12 col-md-5 q-pa-lg bg-primary text-white">
            <div class="full-height flex flex-center column q-py-xl">
              <h4 class="text-center">Already Signed up?</h4>
              <p class="text-center">Sign in to your registered account now!</p>
              <span class="w-3/4">
                <CustomButton label="Sign in" btnClass="secondary-btn" type="reset" :onClick="navigateToSignIn" />
              </span>
            </div>
          </div>

          <div class="col-12 col-md-7 q-pa-lg">
            <div class="full-height flex flex-center column q-py-sm">
              <h2 class="text-center font-black">Sign up for an Account</h2>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                <CustomInput :errorMessage="errors.first_name ? errors.first_name[0] : ''" :modelValue="form.first_name ?? ''" v-model="form.first_name" label="First Name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.last_name ? errors.last_name[0] : ''" :modelValue="form.last_name ?? ''" v-model="form.last_name" label="Last name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.middle_name ? errors.middle_name[0] : ''" :modelValue="form.middle_name ?? ''" v-model="form.middle_name" label="Middle name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.birthdate ? errors.birthdate[0] : ''" :modelValue="form.birthdate ?? ''" v-model="form.birthdate" label="Birthdate" type="date" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.gender ? errors.gender[0] : ''" :modelValue="form.gender ?? ''" v-model="form.gender" label="Gender" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.purok ? errors.purok[0] : ''" :modelValue="form.purok ?? ''" v-model="form.purok" label="Purok" inputClass="col-span-2 md:col-span-1" />
                <CustomSelect :errorMessage="errors.active_voter ? errors.active_voter[0] : ''" :modelValue="form.active_voter ?? ''" v-model="form.active_voter" label="Active Voter?" :options="['Yes', 'No']" selectClass="col-span-2 md:col-span-1" />
                <CustomUploader label="Upload Image" v-model="form.uploadedFiles" uploaderClass="col-span-2"/>
                <CustomInput :errorMessage="errors.email ? errors.email[0] : ''" :modelValue="form.email ?? ''" v-model="form.email" label="Email" type="email" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.username ? errors.username[0] : ''" :modelValue="form.username ?? ''" v-model="form.username" label="Username" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.password ? errors.password[0] : ''" :modelValue="form.password ?? ''" v-model="form.password" label="Password" type="password" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.confirm_password ? errors.confirm_password[0] : ''" :modelValue="form.confirm_password ?? ''" v-model="form.confirm_password" label="Confirm Password" type="password" inputClass="col-span-2 md:col-span-1" />
              </div>

              <CustomButton label="Sign up" type="submit" />
            </div>
          </div>
        </q-form>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { defineAsyncComponent } from 'vue';
import authService from '../../src/authService';

export default {
  name: 'SignUp',
  components: {
    CustomButton: defineAsyncComponent(() => import('components/Widgets/CustomButton.vue')),
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
    CustomUploader: defineAsyncComponent(() => import('components/Widgets/CustomUploader.vue')),
  },
  data() {
    return {
      form: {},
      errors: {},
    };
  },
  watch: {
    form: {
      handler(value, oldValue) {
        this.errors = {};
      },
      deep: true,
    },
  },
  methods: {
    navigateToSignIn() {
      this.$router.push('/');
    },
    async onSubmit() {
      try {
        const response = await authService.register(this.form);
        console.log('Registration successful:', response);

        // Optionally, you can log in the user immediately after registration
        // const user = await authService.getUser();
        // console.log('User:', user);
      } catch (err) {
        const { data } = err.response;
        this.errors = data.errors || {};
        console.error(err);
      }

      /**
       * Test to verify if the logged user can send request to a sanctum protected API
       *
       * NOTE: You can remove this if not needed
       */
      try {
        const user = await authService.getUser();
        console.log('User:', user);
      } catch (err) {
        console.error('Failed to fetch user:', err);
      }
    },
    onReset() {
    },
  },
};
</script>

