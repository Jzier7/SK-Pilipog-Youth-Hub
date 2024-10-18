import { api } from 'src/boot/axios'

const purokService = {
  async getAllPurok(params) {
    const response = await api.get('/api/purok/retrieve/all', { params });
    return response;
  },
};

export default purokService;
