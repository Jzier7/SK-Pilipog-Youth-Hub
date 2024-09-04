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
                <CustomInput v-model="form.email" label="Email" type="email" />
                <CustomInput v-model="form.password" label="Password" type="password" />
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
import { defineAsyncComponent } from 'vue';
import authService from '../../services/authService';

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
      form: {},
      rememberMe: false,
      errors: {},
    };
  },
  methods: {
    async onSubmit() {
      try {
        const { message, body } = await authService.login(this.form);
        console.log(message, body);

        const user = await authService.getUser();
        console.log('User:', user);
      } catch (error) {
        console.error(error);
        if (error.response) {
          this.errors = error.response.data.errors || {};
        } else {
          this.errors = { general: 'An error occurred. Please try again.' };
        }
      }
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

