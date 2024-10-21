import { api } from 'src/boot/axios'

const officialService = {
  async getOfficials(params) {
    const response = await api.get('/api/official/retrieve', { params });
    return response;
  },

  async storeOfficial(data) {
    const response = await api.post('/api/official/store', data );
    return response;
  },

  async updateOfficial(data) {
    const response = await api.patch('/api/official/update', data );
    return response;
  },

  async deleteOfficial(data) {
    const response = await api.delete('/api/official/delete', { data });
    return response;
  },
};

export default officialService;
