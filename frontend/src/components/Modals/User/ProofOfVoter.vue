<template>
  <q-dialog v-model="modalStore.showProofOfVoterModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <h3 class="text-primary pb-4">Proof of Voter</h3>

      <div v-if="localForm.length > 0" class="q-my-md text-center">
        <img
          v-if="getMediaURL(localForm[0]) !== 'No proof of voter available.'"
          :src="getMediaURL(localForm[0])"
          alt="Proof of Voter"
          style="max-width: 100%; max-height: 400px;"
          @click="openLightbox"
        />
        <p v-else>{{ getMediaURL(localForm[0]) }}</p>
      </div>

      <div v-else class="q-my-md text-center">
        <p>No proof of voter available.</p>
      </div>

      <div class="row justify-end">
        <q-btn label="Close" color="negative" @click="closeModal" class="q-ml-sm"></q-btn>
      </div>

      <div v-if="isLightboxOpen" class="lightbox" @click="closeLightbox">
        <span class="close">&times;</span>
        <img
          class="lightbox-content"
          :src="getMediaURL(localForm[0])"
          alt="Proof of Voter"
        />
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { useModalStore } from 'src/stores/modules/modalStore';
import handleMedia from 'src/utils/mixins/handleMedia';

export default {
  props: {
    data: {
      type: Array,
      required: true
    }
  },
  mixins: [handleMedia],
  data() {
    return {
      localForm: [],
      modalStore: useModalStore(),
      isLightboxOpen: false,
    };
  },
  watch: {
    data: {
      immediate: true,
      handler(newValue) {
        this.localForm = [ ...newValue ];
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowProofOfVoterModal(false);
    },
    openLightbox() {
      this.isLightboxOpen = true;
    },
    closeLightbox() {
      this.isLightboxOpen = false;
    },
  },
};
</script>

<style>
.lightbox {
  display: flex;
  position: fixed;
  z-index: 1000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  overflow: auto;
  background-color: rgba(0, 0, 0, 0.8);
  justify-content: center;
  align-items: center;
}
.lightbox-content {
  max-width: 90%;
  max-height: 90%;
}
.close {
  position: absolute;
  top: 10px;
  right: 25px;
  color: white;
  font-size: 30px;
  font-weight: bold;
  cursor: pointer;
}
</style>

