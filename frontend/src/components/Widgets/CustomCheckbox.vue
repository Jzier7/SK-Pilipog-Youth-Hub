<template>
  <q-checkbox
    v-model="inputValue"
    :label="label"
    color="dark"
    :class="`${inputClass} text-dark`"
  />
</template>

<script>
export default {
  name: 'CustomCheckbox',
  props: {
    modelValue: {
      type: [String, Number, Boolean],
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    inputClass: {
      type: String,
      default: '',
    },
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

