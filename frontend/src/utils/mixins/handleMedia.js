export default {
  methods: {
    getMediaURL(media) {
      if (media && media.path) {
        return `${process.env.BACKEND_URL}/storage/${media.path}`;
      } else {
        return 'src/assets/logo.png';
      }
    },
  },
}

