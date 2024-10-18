<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full bg-website">
        <q-form
          @submit="onSubmit"
          @reset="onReset"
          class="row shadow-xl md:w-2/4 w-full"
        >
          <div class="col-12 col-md-7 q-pa-lg">
            <div class="full-height flex flex-center column q-py-xl">
              <img src="~/assets/logo.png" alt="Logo" class="h-auto w-1/2 py-2" />
              <h2 class="text-center text-primary">SK PILIPOG YOUTH HUB</h2>

              <span class="w-3/4">
                <CustomInput
                  :errorMessage="errors.email ? errors.email[0] : ''"
                  v-model="form.email"
                  label="Email"
                  type="email"
                />
                <CustomInput
                  :errorMessage="errors.password ? errors.password[0] : ''"
                  v-model="form.password"
                  label="Password"
                  type="password"
                />
              </span>

              <span class="w-3/4 flex justify-between q-mt-md">
                <CustomCheckbox v-model="rememberMe" label="Remember Me" />
                <CustomButtonLink label="Forgot Password?" :onClick="forgotPassword" />
                <CustomButton label="Sign in" type="submit" />
              </span>
            </div>
          </div>

          <div class="col-12 col-md-5 q-pa-lg bg-primary text-white">
            <div class="full-height flex flex-center column">
              <h4 class="text-center">Don't Have an Account Yet?</h4>
              <p class="text-center">Let's get you all set up!</p>
              <span class="w-3/4">
                <CustomButton
                  label="Sign up"
                  type="reset"
                  btnClass="secondary-btn"
                  :onClick="navigateToSignUp"
                />
              </span>
            </div>
          </div>
        </q-form>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<script>
import { Notify } from 'quasar'
import { defineAsyncComponent } from 'vue';
import { useAuthStore } from 'src/stores/modules/authStore';
import { USER_ROLES } from 'src/utils/constants';

export default {
  name: 'SignIn',
  components: {
    CustomButton: defineAsyncComponent(() => import('components/Widgets/CustomButton.vue')),
    CustomButtonLink: defineAsyncComponent(() => import('components/Widgets/CustomButtonLink.vue')),
    CustomInput: defineAsyncComponent(() => import('components/Widgets/CustomInput.vue')),
    CustomCheckbox: defineAsyncComponent(() => import('components/Widgets/CustomCheckbox.vue')),
  },
  data() {
    return {
      form: {
        email: '',
        password: '',
      },
      rememberMe: false,
      errors: {},
    };
  },
  methods: {
    async onSubmit() {
      this.errors = {}; // Reset errors

      // Perform validation
      if (!this.validateForm()) {
        return; // Stop the submission if validation fails
      }

      try {
        const authStore = useAuthStore();
        const { data, message } = await authStore.login(this.form);

        Notify.create({
          type: 'positive',
          position: 'top',
          message: message
        });

        if (data?.role.slug === USER_ROLES.SUPERADMIN) {
          this.$router.push('/superadmin/dashboard');
        } else if (data?.role.slug === USER_ROLES.ADMIN) {
          this.$router.push('/admin/dashboard');
        } else {
          this.$router.push('/user/home');
        }

      } catch (error) {
        console.error(error);
        if (error.response) {
          this.errors = error.response.data.errors || {};
        } else {
          Notify.create({
            type: 'negative',
            position: 'top',
            message: 'An error occurred. Please try again.'
          });
        }
      }
    },
    validateForm() {
      let isValid = true;

      if (!this.form.email) {
        this.errors.email = ['Email is required.'];
        isValid = false;
      } else if (!this.isEmailValid(this.form.email)) {
        this.errors.email = ['Email is not valid.'];
        isValid = false;
      }

      if (!this.form.password) {
        this.errors.password = ['Password is required.'];
        isValid = false;
      } else if (this.form.password.length < 6) {
        this.errors.password = ['Password must be at least 6 characters long.'];
        isValid = false;
      }

      return isValid;
    },
    isEmailValid(email) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return emailRegex.test(email);
    },
    navigateToSignUp() {
      this.$router.push('/signup');
    },
    forgotPassword() {
      // Implement forgot password logic
    },
  },
};
</script>

