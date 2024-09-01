<template>
  <q-input
    v-model="inputValue"
    :label="label"
    :type="type"
    :outlined="outlined"
    :standout="standout"
    :class="`${inputClass} q-mt-md w-full`"
    :error-message="errorMessage"
    :error="errorMessage !== '' ?  true : false"
  />
</template>

<script>
export default {
  name: 'CustomInput',
  data() {
    return {
    }
  },
  props: {
    modelValue: {
      type: [String, Number],
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    type: {
      type: String,
      default: 'text',
    },
    outlined: {
      type: Boolean,
      default: true,
    },
    standout: {
      type: String,
      default: '',
    },
    inputClass: {
      type: String,
      default: '',
    },
    errorMessage: {
      type: String,
      default: ''
    }
  },
  computed: {
    inputValue: {
      get() {
        return this.modelValue;
      },
      set(value) {
        this.$emit('update:modelValue', value);
      },
    },
  },
}
</script>
