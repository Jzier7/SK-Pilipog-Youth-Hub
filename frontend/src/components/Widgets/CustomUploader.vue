<template>
  <div :class="`${uploaderClass} w-full`">
    <q-uploader
      :label="label"
      :color="color"
      :hide-upload-btn="hideUploadBtn"
      class="w-full"
      v-model="files"
      @added="onFilesAdded"
      :file-sizes="fileSizes"
      :max-files="maxFiles"
      @removed="onFileRemoved"
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
    maxFiles: {
      type: Number,
      default: 10,
    },
    fileSizes: {
      type: Object,
      default: () => ({})
    }
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
    onFilesAdded(newFiles) {
      this.files = [...this.files, ...newFiles];
      this.$emit('update:modelValue', this.files);
    },
    onFileRemoved(file) {
      const index = this.files.indexOf(file);
      if (index !== -1) {
        this.files.splice(index, 1);
        this.$emit('update:modelValue', this.files);
      }
    }
  },
}
</script>

<style scoped>
</style>

