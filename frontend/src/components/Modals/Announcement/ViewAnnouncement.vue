<template>
  <q-dialog v-model="modalStore.showViewAnnouncementModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 700px; max-width: 80vw;">
      <div>
        <h4 class="font-bold text-primary">{{ localForm.title }}</h4>
        <p class="text-gray-700 q-mb-lg">{{ localForm.categoryName }}</p>
        <div v-html="localForm.content" class="q-mb-md text-black"></div>
      </div>
      <div class="row justify-end">
        <q-btn label="Close" color="negative" @click="closeModal"></q-btn>
      </div>
    </q-card>
  </q-dialog>
</template>

<script>
import { useModalStore } from 'src/stores/modules/modalStore';

export default {
  props: {
    viewData: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      localForm: {
        title: '',
        categoryName: 'Unknown Category',
        content: ''
      },
      modalStore: useModalStore(),
    };
  },
  watch: {
    viewData: {
      immediate: true,
      handler(newValue) {
        this.localForm.title = newValue.title;
        this.localForm.categoryName = newValue.category ? newValue.category.name : 'Unknown Category';
        this.localForm.content = newValue.content;
      }
    }
  },
  methods: {
    closeModal() {
      this.modalStore.setShowViewAnnouncementModal(false);
    }
  },
};
</script>
