<template>
  <q-header elevated>
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
      <span class="flex items-center">
        <CustomButtonLink label="logout" color="white" />
      </span>
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

