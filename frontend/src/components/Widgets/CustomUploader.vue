<template>
  <q-uploader
    :label="label"
    :color="color"
    :hide-upload-btn="hideUploadBtn"
    :class="`${uploaderClass} q-mt-md w-full`"
    v-model="files"
  />
</template>

<script>
export default {
  name: 'CustomUploader',
  props: {
    label: {
      type: String,
      required: true,
    },
    color: {
      type: String,
      default: 'primary',
    },
    hideUploadBtn: {
      type: Boolean,
      default: true,
    },
    uploaderClass: {
      type: String,
      default: '',
    },
    modelValue: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      files: this.modelValue,
    };
  },
  watch: {
    modelValue: {
      immediate: true,
      handler(newValue) {
        this.files = newValue;
      },
    },
    files(newValue) {
      this.$emit('update:modelValue', newValue);
    },
  },
}
</script>

