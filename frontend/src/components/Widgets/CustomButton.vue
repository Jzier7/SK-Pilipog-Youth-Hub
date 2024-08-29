<template>
  <q-btn
    :label="label"
    :color="color"
    :type="type"
    :class="`${btnClass} q-mt-md w-full py-3 font-bold`"
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
    },
    type: {
      type: String,
      default: ''
    }
  }
}
</script>

<style lang="scss">
.primary-btn {
  background-color: $primary !important;
  color: white !important;
}

.secondary-btn {
  background-color: white !important;
  color: $primary !important;
}
</style>

