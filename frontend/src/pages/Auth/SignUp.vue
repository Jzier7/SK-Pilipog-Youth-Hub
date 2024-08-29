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
                <CustomInput v-model="firstName" label="Firstname" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="lastName" label="Last name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="middleName" label="Middle name" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="birthdate" label="Birthdate" type="date" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="gender" label="Gender" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="purok" label="Purok" inputClass="col-span-2 md:col-span-1" />
                <CustomSelect v-model="activeVoter" label="Active Voter?" :options="['Yes', 'No']" selectClass="col-span-2 md:col-span-1" />
                <CustomUploader label="Upload Image" v-model="uploadedFiles" uploaderClass="col-span-2"/>
                <CustomInput v-model="email" label="Email" type="email" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="username" label="Username" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="password" label="Password" type="password" inputClass="col-span-2 md:col-span-1" />
                <CustomInput v-model="confirmPassword" label="Confirm Password" type="password" inputClass="col-span-2 md:col-span-1" />
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
import { defineAsyncComponent } from 'vue'

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
      firstName: '',
      middleName: '',
      lastName: '',
      birthdate: '',
      gender: '',
      email: '',
      purok: '',
      activeVoter: '',
      username: '',
      password: '',
      confirmPassword: '',
    };
  },
  methods: {
    signUp() {
    },
    navigateToSignIn() {
      this.$router.push('/');
    }
  }
}
</script>
