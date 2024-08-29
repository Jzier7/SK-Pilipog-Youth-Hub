<template>
  <q-select
    :v-model="selectValue"
    :label="label"
    :outlined="outlined"
    :options="options"
    :class="`${selectClass}`"
  />
</template>

<script>
export default {
  name: 'CustomSelect',
  props: {
    modelValue: {
      type: [String, Number],
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    options: {
      type: Array,
      required: true,
    },
    outlined: {
      type: Boolean,
      default: true,
    },
    selectClass: {
      type: String,
      default: '',
    },
  },
  computed: {
    selectValue: {
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
