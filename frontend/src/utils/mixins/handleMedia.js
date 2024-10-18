export default {
  methods: {
    getMediaURL(media) {
      if (media && media.path) {
        return `${process.env.BACKEND_URL}/storage/${media.path}`;
      } else {
        return 'No proof of voter available.';
      }
    },
  },
}

