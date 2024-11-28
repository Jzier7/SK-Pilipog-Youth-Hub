import { api } from 'src/boot/axios'

const purokService = {
  async getPaginatedPuroks(params) {
    const response = await api.get('/api/purok/retrieve/paginated', { params });
    return response;
  },

  async getAllPurok() {
    const response = await api.get('/api/purok/retrieve/all');
    return response;
  },

  async storePurok(data) {
    const response = await api.post('/api/purok/store', data );
    return response;
  },

  async updatePurok(data) {
    const response = await api.patch('/api/purok/update', data );
    return response;
  },

  async deletePurok(data) {
    const response = await api.delete('/api/purok/delete', { data });
    return response;
  },
};

export default purokService;
