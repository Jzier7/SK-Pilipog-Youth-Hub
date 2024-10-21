import { api } from 'src/boot/axios'

const termService = {
  async getTerms(params) {
    const response = await api.get('/api/term/retrieve', { params });
    return response;
  },

  async storeTerm(data) {
    const response = await api.post('/api/term/store', data );
    return response;
  },

  async updateTerm(data) {
    const response = await api.patch('/api/term/update', data );
    return response;
  },

  async deleteTerm(data) {
    const response = await api.delete('/api/term/delete', { data });
    return response;
  },

  async setActiveTerm(data) {
    const response = await api.patch('/api/term/update/status', data );
    return response;
  },
};

export default termService;
