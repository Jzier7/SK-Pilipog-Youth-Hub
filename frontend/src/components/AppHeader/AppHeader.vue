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
        Quasar App
      </q-toolbar-title>
      <div>Quasar v{{ version }}</div>
    </q-toolbar>
  </q-header>
</template>

<script>
export default {
  name: 'AppHeader',
  props: {
    version: {
      type: String,
      required: true
    }
  }
}
</script>

