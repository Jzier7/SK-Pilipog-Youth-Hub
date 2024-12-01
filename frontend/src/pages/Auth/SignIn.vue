<template>
  <q-layout>
    <q-page-container>
      <q-page class="flex justify-center items-center w-full bg-website">
        <q-form @submit="onSubmit" class="row shadow-xl md:w-2/4 w-full">
          <div class="col-12 col-md-7 q-pa-lg">
            <div class="full-height flex flex-center column q-py-xl">
              <img src="~/assets/logo.png" alt="Logo" class="h-auto w-1/2 py-2" />
              <h2 class="text-center text-primary">SK PILIPOG YOUTH HUB</h2>

              <span class="w-3/4">
                <CustomInput :errorMessage="errors.email ? errors.email[0] : ''" v-model="form.email" label="Email"
                  type="email" />
                <CustomInput :errorMessage="errors.password ? errors.password[0] : ''" v-model="form.password"
                  label="Password" type="password" />
              </span>

              <span class="w-3/4 q-mt-md">
                <CustomCheckbox v-model="form.rememberMe" label="Remember Me" />
                <CustomButton label="Sign in" type="submit" />

                <div class="w-full text-center mt-2">
                  <CustomButtonLink label="Login as Guest" :onClick="loginAsGuest" />
                </div>

                <div class="q-mt-md text-center">
                  <CustomButtonLink flat label="Forgot Password?" @click="navigateToForgotPassword" />
                </div>
              </span>
            </div>
          </div>

          <div class="col-12 col-md-5 q-pa-lg bg-primary text-white">
            <div class="full-height flex flex-center column">
              <h4 class="text-center">Don't Have an Account Yet?</h4>
              <p class="text-center">Let's get you all set up!</p>
              <span class="w-3/4">
                <CustomButton label="Sign up" type="reset" btnClass="secondary-btn" :onClick="navigateToSignUp" />
              </span>
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
        rememberMe: false,
      },
      errors: {},
    };
  },
  methods: {
    async onSubmit() {
      this.errors = {};

      try {
        const authStore = useAuthStore();
        const { data, message, status } = await authStore.login(this.form);

        if (status === 401 || status === 422) {
          Notify.create({
            type: 'negative',
            position: 'top',
            message: message || 'Invalid credentials. Please check your inputs.'
          });
        } else {
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
        }
      } catch (error) {
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
    async loginAsGuest() {
      try {
        const authStore = useAuthStore();
        const { message, status } = await authStore.loginAsGuest();

        Notify.create({
          type: 'positive',
          position: 'top',
          message: message
        });
        this.$router.push('/guest/home');
      } catch (error) {
        Notify.create({
          type: 'negative',
          position: 'top',
          message: 'An error occurred. Please try again.'
        });
      }
    },
    navigateToSignUp() {
      this.$router.push('/signup');
    },
    navigateToForgotPassword() {
      this.$router.push('/forgot-password');
    }
  },
};
</script>
