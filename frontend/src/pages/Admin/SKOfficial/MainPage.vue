<template>
  <q-page padding>
    <q-toolbar class="q-pa-none">
      <q-toolbar-title>
        <h3 class="text-primary">{{ currentTitle }}</h3>
      </q-toolbar-title>
    </q-toolbar>

    <div>
      <q-tabs v-model="activeTab" dense class="text-primary q-pb-sm" active-color="primary">
        <q-tab name="officials" label="Officials" />
        <q-tab name="positions" label="Positions" />
        <q-tab name="terms" label="Terms" />
      </q-tabs>


      <q-tab-panels v-model="activeTab" class="border">
        <q-tab-panel name="officials">
          <OfficialsComponent />
        </q-tab-panel>
        <q-tab-panel name="positions">
          <PositionsComponent />
        </q-tab-panel>
        <q-tab-panel name="terms">
          <TermsComponent />
        </q-tab-panel>
      </q-tab-panels>
    </div>
  </q-page>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  components: {
    OfficialsComponent: defineAsyncComponent(() => import('./Components/SKOfficial.vue')),
    TermsComponent: defineAsyncComponent(() => import('./Components/AdminTerm.vue')),
    PositionsComponent: defineAsyncComponent(() => import('./Components/AdminPosition.vue')),
  },
  data() {
    return {
      activeTab: 'officials',
    };
  },
  computed: {
    currentTitle() {
      switch (this.activeTab) {
        case 'officials':
          return 'SK OFFICIALS';
        case 'positions':
          return 'POSITIONS';
        case 'terms':
          return 'TERMS';
        default:
          return '';
      }
    },
  },
};
</script>

<style scoped>
.text-bold {
  font-weight: bold;
}
</style>

