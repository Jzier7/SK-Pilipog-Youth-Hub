<template>
  <q-btn
    :label="label"
    :class="`${btnClass} lowercase`"
    flat
    :color="color"
    @click="onClick"
  />
</template>

<script>
export default {
  name: 'CustomButton',
  props: {
    label: {
      type: String,
      required: true
    },
    color: {
      type: String,
      default: 'primary'
    },
    btnClass: {
      type: String,
      default: ''
    },
    onClick: {
      type: Function,
      default: null
    }
  }
}
</script>
