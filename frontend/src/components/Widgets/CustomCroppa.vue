<template>
  <div>
    <label class="mb-1 block">Image upload:</label>
    <div v-if="!imgUrl" class="col-span-4 mb-4 w-full">
      <div
        v-if="!cropping"
        @dragover.prevent
        @drop.prevent="onDrop"
        @click="$refs.fileInput.click()"
        class="upload-box mb-4 flex items-center justify-center cursor-pointer"
      >
        <input
          ref="fileInput"
          type="file"
          accept="image/*"
          @change="onFileChange"
          class="hidden"
        />
        <p>Drag & Drop your image here or click to upload</p>
      </div>

      <cropper
        ref="cropper"
        class="cropper"
        :src="img"
        :stencil-size="stencilSize"
        :stencil-props="stencilProps"
      />

      <div v-if="cropping" class="flex justify-start mt-2">
        <q-btn @click="rotateImage(-90)" icon="rotate_left" />
        <q-btn @click="rotateImage(90)" icon="rotate_right" />
        <q-btn @click="flipImage('x')" icon="flip" />
        <q-btn @click="flipImage('y')" icon="flip" />
        <q-btn @click="zoomImage(2)" icon="zoom_in" />
        <q-btn @click="zoomImage(0.5)" icon="zoom_out" />
        <q-btn @click="clearImage" label="Clear" />
      </div>

      <div class="mt-4">
        <q-btn v-if="cropping" color="primary" label="Generate" @click="generateImage" />
      </div>
    </div>

    <div v-if="imgUrl" class="mt-4">
      <img
          :src="imgUrl"
          alt="Cropped Image"
          class="w-full mb-4"
          :class="{'circular-image': isCircular}"
          />
      <q-btn color="negative" label="Remove Image" @click="removeImage" />
    </div>
  </div>
</template>

<script>
import { Notify } from 'quasar';
import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';
import 'vue-advanced-cropper/dist/theme.compact.css';

export default {
  components: {
    Cropper,
  },
  props: {
    existingImage: {
      type: String,
      default: ''
    },
    stencilSize: {
      type: Object,
    },
    stencilProps: {
      type: Object,
    },
    isCircular: {
      type: Boolean,
      defualt: false
    }
  },
  data() {
    return {
      img: '',
      imgUrl: '',
      cropping: false,
    };
  },
  mounted() {
    if (this.existingImage) {
      this.imgUrl = this.existingImage;
      this.img = this.existingImage;
    }
  },
  methods: {
    onFileChange(event) {
      const file = event.target.files[0];
      this.handleFile(file);
    },

    onDrop(event) {
      const file = event.dataTransfer.files[0];
      this.handleFile(file);
    },

    handleFile(file) {
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          this.img = reader.result;
          this.cropping = true;
        };
        reader.readAsDataURL(file);
      }
    },

    rotateImage(direction) {
      if (this.$refs.cropper) {
        this.$refs.cropper.rotate(direction);
      }
    },

    flipImage(axis) {
      if (this.$refs.cropper) {
        axis === 'x' ? this.$refs.cropper.flip(true, false) : this.$refs.cropper.flip(false, true);
      }
    },

    zoomImage(factor) {
      if (this.$refs.cropper) {
        this.$refs.cropper.zoom(factor);
      }
    },

    clearImage() {
      this.img = '';
      this.cropping = false;
    },

    async generateImage() {
      if (this.$refs.cropper) {
        const cropperInstance = this.$refs.cropper;
        const croppedCanvas = cropperInstance.getCanvas();

        if (croppedCanvas) {
          croppedCanvas.toBlob(blob => {
            this.$emit('imageCropped', blob);
            const blobUrl = URL.createObjectURL(blob);
            this.imgUrl = blobUrl; // Directly update imgUrl with the new blob URL
          }, 'image/png');
        } else {
          Notify.create({
            message: 'Error generating the cropped image.',
            color: 'negative',
          });
        }
      }
    },

    removeImage() {
      if (this.imgUrl) {
        URL.revokeObjectURL(this.imgUrl); // Revoke the URL if it exists
      }
      this.imgUrl = '';
      this.img = '';
      this.cropping = false;
    },
  },
};
</script>

<style scoped>
.upload-box {
  width: 100%;
  height: 200px;
  border: 2px dashed #ccc;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #666;
  text-align: center;
}
.upload-box:hover {
  border-color: #999;
}
.cropper {
  max-width: 100%;
  height: auto;
}
.circular-image {
  border-radius: 50%;
  object-fit: cover;
  width: 250px;
  height: 250px;
}
</style>

