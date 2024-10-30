<template>
  <q-dialog v-model="modalStore.showViewTeamModal">
    <q-card flat bordered class="q-pa-md text-white" style="width: 400px; max-width: 80vw;">
      <h3 class="text-primary pb-4">{{ viewData.name }}</h3>
      <div class="text-primary">
        <h4>Players:</h4>
        <ul>
          <li v-for="player in viewData.players" :key="player.id">{{ player.name }}</li>
        </ul>
      </div>
      <div class="row justify-end">
        <q-btn label="Close" color="grey" @click="closeModal"></q-btn>
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
      modalStore: useModalStore(),
    };
  },
  methods: {
    closeModal() {
      this.modalStore.setShowViewTeamModal(false);
    }
  }
};
</script>

<style lang="scss" scoped>
.text-bold {
  font-weight: bold;
}
</style>
