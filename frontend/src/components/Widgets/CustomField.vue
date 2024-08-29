<template>
  <q-input
    :standout="standout"
    v-model="inputValue"
    :label="label"
    :type="type"
    :flat="flat"
    :class="inputClass"
  />
</template>

<script>
export default {
  name: 'CustomField',
  props: {
    modelValue: {
      type: [String, Number],
      required: true
    },
    label: {
      type: String,
      required: true
    },
    type: {
      type: String,
      default: 'text'
    },
    flat: {
      type: Boolean,
      default: true,
    },
    standout: {
      type: String,
      default: ''
    },
    inputClass: {
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
      }
    }
  }
}
</script>
