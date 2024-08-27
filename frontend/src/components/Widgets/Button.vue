<template>
  <q-btn
    :label="label"
    :icon="icon"
    :color="color"
    :size="size"
    :flat="flat"
    :rounded="rounded"
    :disabled="disabled"
    @click="handleClick"
  />
</template>

<script>
export default {
  name: 'ButtonWidget',
  props: {
    label: {
      type: String,
      required: true
    },
    icon: {
      type: String,
      default: ''
    },
    color: {
      type: String,
      default: 'primary'
    },
    size: {
      type: String,
      default: 'md'
    },
    flat: {
      type: Boolean,
      default: false
    },
    rounded: {
      type: Boolean,
      default: false
    },
    disabled: {
      type: Boolean,
      default: false
    }
  },
  methods: {
    handleClick() {
      this.$emit('button-clicked');
    }
  }
}
</script>
