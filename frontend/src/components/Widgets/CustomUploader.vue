<template>
  <div :class="`${uploaderClass} w-full`">
    <q-uploader
      :label="label"
      :color="color"
      :hide-upload-btn="hideUploadBtn"
      class="w-full"
      v-model="files"
      @added="onFilesAdded"
    />
    <span v-if="errorMessage" class="text-negative text-xs px-4">{{ errorMessage }}</span>
  </div>
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
    errorMessage: {
      type: String,
      default: '',
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
  methods: {
    onFilesAdded(files) {
      this.files = files;
      this.$emit('update:modelValue', files);
    },
  },
}
</script>
