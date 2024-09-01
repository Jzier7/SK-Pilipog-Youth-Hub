<template>
  <q-header elevated class="py-3">
    <q-toolbar>
      <q-btn
        flat
        dense
        round
        icon="menu"
        aria-label="Menu"
        @click="$emit('toggle-drawer')"
      />
      <q-toolbar-title>
        PILIPOG YOUTH HUB
      </q-toolbar-title>
      <CustomButtonLink icon="logout" label="logout" color="white" class="py-2" />
    </q-toolbar>
  </q-header>
</template>

<script>
import { defineAsyncComponent } from 'vue';

export default {
  name: 'AppHeader',
  components: {
    CustomButtonLink: defineAsyncComponent(() => import('components/Widgets/CustomButtonLink.vue')),
  },
  props: {
    version: {
      type: String,
      required: true
    }
  }
}
</script>

