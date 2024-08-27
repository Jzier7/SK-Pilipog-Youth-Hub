<template>
  <q-drawer
    :model-value="drawerOpen"
    show-if-above
    bordered
    @update:model-value="$emit('update:drawerOpen', $event)"
  >
    <q-list>
      <q-item-label header>
        Essential Links
      </q-item-label>
    </q-list>
  </q-drawer>
</template>

<script>

export default {
  name: 'SideNav',
  props: {
    links: {
      type: Array,
      required: true
    },
    drawerOpen: {
      type: Boolean,
      required: true
    }
  }
}
</script>
