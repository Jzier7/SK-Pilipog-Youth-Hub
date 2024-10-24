<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full">
        <q-form
          @submit="onSubmit"
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
              <h2 class="text-center text-primary">Sign up for an Account</h2>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full q-pt-md">
                <CustomInput :errorMessage="errors.first_name ? errors.first_name[0] : ''" v-model="form.first_name" label="First Name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.last_name ? errors.last_name[0] : ''" v-model="form.last_name" label="Last Name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.middle_name ? errors.middle_name[0] : ''" v-model="form.middle_name" label="Middle Name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.birthdate ? errors.birthdate[0] : ''" v-model="form.birthdate" label="Birthdate" type="date" inputClass="col-span-2 md:col-span-1" />
                <CustomSelect
                  v-model="form.gender"
                  :options="genderOptions"
                  label="Gender"
                  :errorMessage="errors.gender ? errors.gender[0] : ''"
                />
                <CustomSelect
                  v-model="form.purok"
                  :options="purokData.map(purok => ({ label: purok.name, value: purok.id }))"
                  label="Purok"
                  :errorMessage="errors.purok ? errors.purok[0] : ''"
                />
                <CustomUploader
                  label="Proof of Voter"
                  v-model="form.files"
                  :errorMessage="errors.files ? errors.files[0] : ''"
                  uploaderClass="col-span-2"
                />
                <CustomInput :errorMessage="errors.email ? errors.email[0] : ''" v-model="form.email" label="Email" type="email" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.username ? errors.username[0] : ''" v-model="form.username" label="Username" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.password ? errors.password[0] : ''" v-model="form.password" label="Password" type="password" inputClass="col-span-2 md:col-span-1" />
                <CustomInput :errorMessage="errors.confirm_password ? errors.confirm_password[0] : ''" v-model="form.confirm_password" label="Confirm Password" type="password" inputClass="col-span-2 md:col-span-1" />
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
import { Notify } from 'quasar';
import { defineAsyncComponent } from 'vue';
import authService from '../../services/authService';
import purokService from 'src/services/purokService';

export default {
  name: 'SignUp',
  components: {
    CustomButton: defineAsyncComponent(() => import('components/Widgets/CustomButton.vue')),
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomUploader: defineAsyncComponent(() => import('components/Widgets/CustomUploader.vue')),
    CustomSelect: defineAsyncComponent(() => import('components/Widgets/CustomSelect.vue')),
  },
  data() {
    return {
      form: {
        first_name: '',
        last_name: '',
        middle_name: '',
        birthdate: '',
        gender: '',
        purok: '',
        files: [],
        email: '',
        username: '',
        password: '',
        confirm_password: '',
      },
      purokData: [],
      genderOptions: [
        { label: 'Select Gender', value: '' },
        { label: 'Male', value: 'male' },
        { label: 'Female', value: 'female' },
      ],
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
  mounted() {
    this.fetchPurok();
  },
  methods: {
    navigateToSignIn() {
      this.$router.push('/');
    },
    async onSubmit() {
      try {
        const formData = new FormData();

        Object.keys(this.form).forEach((key) => {
          if (key === 'files' && this.form.files.length > 0) {
            this.form.files.forEach((file, index) => {
              formData.append(`files[${index}]`, file);
            });
          } else {
            formData.append(key, this.form[key]);
          }
        });

        const { message } = await authService.register(formData);

        Notify.create({
          type: 'positive',
          position: 'top',
          message: message,
        });

        this.$router.push('/');
      } catch (err) {
        const { data } = err.response;
        this.errors = data.errors || {};
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
  }
};
</script>

